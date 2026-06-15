<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Consent;
use App\Models\UserProfile;
use App\Models\Verification;
use App\Models\VerificationFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

class UserVerificationController extends Controller
{
    public function show(Request $request): Response
    {
        $user = $request->user();
        $verification = $user->verifications()
            ->with(['files', 'reviews' => fn ($query) => $query->latest('decided_at')->latest()])
            ->latest('attempt_number')
            ->first();

        return Inertia::render('app/dashboard', [
            'profile' => $user->profile,
            'verification' => $verification ? [
                'id' => $verification->id,
                'attempt_number' => $verification->attempt_number,
                'document_type' => $verification->document_type,
                'status' => $verification->status,
                'verification_code' => $verification->status === Verification::STATUS_APPROVED ? $verification->verification_code : null,
                'submitted_at' => $verification->submitted_at?->toISOString(),
                'approved_at' => $verification->approved_at?->toISOString(),
                'expires_at' => $verification->expires_at?->toISOString(),
                'files' => $verification->files->map(fn (VerificationFile $file): array => [
                    'file_type' => $file->file_type,
                    'mime_type' => $file->mime_type,
                    'size_bytes' => $file->size_bytes,
                ])->values(),
                'latest_review' => $verification->reviews->first() ? [
                    'decision' => $verification->reviews->first()->decision,
                    'reason' => $verification->reviews->first()->reason,
                    'notes' => $verification->reviews->first()->notes,
                ] : null,
            ] : null,
            'can_submit' => ! $verification || in_array($verification->status, [
                Verification::STATUS_PENDING,
                Verification::STATUS_REJECTED,
                Verification::STATUS_CORRECTION_REQUESTED,
            ], true),
            'status_options' => [
                Verification::STATUS_PENDING,
                Verification::STATUS_UNDER_REVIEW,
                Verification::STATUS_APPROVED,
                Verification::STATUS_REJECTED,
                Verification::STATUS_CORRECTION_REQUESTED,
                Verification::STATUS_BLOCKED,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $profile = $user->profile;

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'cpf' => [
                'required',
                'digits:11',
                Rule::unique(UserProfile::class, 'cpf')->ignore($profile?->id),
            ],
            'birth_date' => ['required', 'date', 'before:-18 years'],
            'phone' => ['required', 'string', 'max:20'],
            'document_type' => ['required', Rule::in(['rg', 'cnh'])],
            'document_front' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'document_back' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'selfie' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'accept_terms' => ['accepted'],
            'accept_privacy' => ['accepted'],
        ]);

        $latestVerification = $user->verifications()->latest('attempt_number')->first();

        if ($latestVerification && in_array($latestVerification->status, [
            Verification::STATUS_UNDER_REVIEW,
            Verification::STATUS_APPROVED,
            Verification::STATUS_BLOCKED,
        ], true)) {
            throw ValidationException::withMessages([
                'verification' => 'Sua verificacao atual nao permite novo envio neste momento.',
            ]);
        }

        DB::transaction(function () use ($request, $user, $validated, $latestVerification): void {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $validated['full_name'],
                    'cpf' => $validated['cpf'],
                    'birth_date' => $validated['birth_date'],
                    'phone' => $validated['phone'],
                ],
            );

            foreach (['terms_of_use', 'privacy_policy'] as $consentType) {
                $consent = Consent::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'type' => $consentType,
                        'version' => '1.0',
                    ],
                    [
                        'accepted_at' => now(),
                        'ip_address' => $request->ip(),
                    ],
                );

                activity()
                    ->performedOn($consent)
                    ->causedBy($user)
                    ->event('accepted')
                    ->withProperties([
                        'type' => $consentType,
                        'version' => '1.0',
                    ])
                    ->log('consent.accepted');
            }

            $verification = $user->verifications()->create([
                'attempt_number' => ($latestVerification?->attempt_number ?? 0) + 1,
                'document_type' => $validated['document_type'],
                'status' => Verification::STATUS_UNDER_REVIEW,
                'submitted_at' => now(),
            ]);

            $this->storeVerificationFile($verification, VerificationFile::TYPE_FRONT, $request->file('document_front'));
            $this->storeVerificationFile($verification, VerificationFile::TYPE_BACK, $request->file('document_back'));
            $this->storeVerificationFile($verification, VerificationFile::TYPE_SELFIE, $request->file('selfie'));

            activity()
                ->performedOn($verification)
                ->causedBy($user)
                ->event('documents_uploaded')
                ->withProperties([
                    'document_type' => $verification->document_type,
                    'attempt_number' => $verification->attempt_number,
                    'file_types' => [
                        VerificationFile::TYPE_FRONT,
                        VerificationFile::TYPE_BACK,
                        VerificationFile::TYPE_SELFIE,
                    ],
                ])
                ->log('verification.documents_uploaded');
        });

        return to_route('app.dashboard')->with('status', 'verification-submitted');
    }

    private function storeVerificationFile(Verification $verification, string $fileType, UploadedFile $uploadedFile): void
    {
        $extension = $uploadedFile->extension() ?: $uploadedFile->guessExtension() ?: 'jpg';
        $path = Storage::disk('s3')->putFileAs(
            "verifications/{$verification->id}",
            $uploadedFile,
            "{$fileType}.{$extension}",
            ['visibility' => 'private'],
        );

        if (! is_string($path) || $path === '') {
            throw new RuntimeException('Nao foi possivel armazenar o arquivo de verificacao.');
        }

        $verification->files()->create([
            'file_type' => $fileType,
            'disk' => 's3',
            'path' => $path,
            'mime_type' => $uploadedFile->getMimeType() ?: 'application/octet-stream',
            'size_bytes' => $uploadedFile->getSize(),
            'uploaded_at' => now(),
        ]);
    }
}
