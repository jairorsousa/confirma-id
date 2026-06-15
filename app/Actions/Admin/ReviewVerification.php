<?php

namespace App\Actions\Admin;

use App\Models\User;
use App\Models\Verification;
use App\Models\VerificationReview;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReviewVerification
{
    public function approve(Verification $verification, User $admin, ?string $notes = null): Verification
    {
        return $this->recordDecision(
            verification: $verification,
            admin: $admin,
            decision: VerificationReview::DECISION_APPROVED,
            status: Verification::STATUS_APPROVED,
            reason: null,
            notes: $notes,
        );
    }

    public function reject(Verification $verification, User $admin, string $reason, ?string $notes = null): Verification
    {
        return $this->recordDecision(
            verification: $verification,
            admin: $admin,
            decision: VerificationReview::DECISION_REJECTED,
            status: Verification::STATUS_REJECTED,
            reason: $reason,
            notes: $notes,
        );
    }

    public function requestCorrection(Verification $verification, User $admin, string $reason, ?string $notes = null): Verification
    {
        return $this->recordDecision(
            verification: $verification,
            admin: $admin,
            decision: VerificationReview::DECISION_CORRECTION_REQUESTED,
            status: Verification::STATUS_CORRECTION_REQUESTED,
            reason: $reason,
            notes: $notes,
        );
    }

    public function block(Verification $verification, User $admin, string $reason, ?string $notes = null): Verification
    {
        return $this->recordDecision(
            verification: $verification,
            admin: $admin,
            decision: VerificationReview::DECISION_BLOCKED,
            status: Verification::STATUS_BLOCKED,
            reason: $reason,
            notes: $notes,
        );
    }

    private function recordDecision(
        Verification $verification,
        User $admin,
        string $decision,
        string $status,
        ?string $reason = null,
        ?string $notes = null,
    ): Verification {
        if ($decision !== VerificationReview::DECISION_APPROVED && blank($reason)) {
            throw ValidationException::withMessages([
                'reason' => 'Informe o motivo da decisao.',
            ]);
        }

        return DB::transaction(function () use ($verification, $admin, $decision, $status, $reason, $notes): Verification {
            $verification->forceFill([
                'status' => $status,
                'verification_code' => $status === Verification::STATUS_APPROVED
                    ? ($verification->verification_code ?: $this->generateCode())
                    : $verification->verification_code,
                'approved_at' => $status === Verification::STATUS_APPROVED ? now() : null,
                'expires_at' => $status === Verification::STATUS_APPROVED ? now()->addYear() : null,
            ])->save();

            $review = $verification->reviews()->create([
                'admin_id' => $admin->id,
                'decision' => $decision,
                'reason' => $reason,
                'notes' => $notes,
                'decided_at' => now(),
            ]);

            activity()
                ->performedOn($verification)
                ->causedBy($admin)
                ->event($decision)
                ->withProperties([
                    'status' => $status,
                    'reason' => $reason,
                    'review_id' => $review->id,
                ])
                ->log("verification.{$decision}");

            return $verification->refresh();
        });
    }

    private function generateCode(): string
    {
        do {
            $code = 'CID-'.str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Verification::where('verification_code', $code)->exists());

        return $code;
    }
}
