<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Verification;
use App\Notifications\VerificationStatusNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UserVerificationJourneyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        Notification::fake();
        Storage::fake('s3');
    }

    public function test_user_can_submit_a_complete_verification(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->post(route('app.verification.store'), [
            'full_name' => 'Pessoa Teste',
            'cpf' => '12345678901',
            'birth_date' => '1990-01-01',
            'phone' => '11999999999',
            'document_type' => 'rg',
            'document_front' => UploadedFile::fake()->image('front.jpg')->size(512),
            'document_back' => UploadedFile::fake()->image('back.jpg')->size(512),
            'selfie' => UploadedFile::fake()->image('selfie.jpg')->size(512),
            'accept_terms' => '1',
            'accept_privacy' => '1',
        ]);

        $response->assertRedirect(route('app.dashboard', absolute: false));

        $verification = $user->verifications()->with('files')->firstOrFail();

        $this->assertSame(Verification::STATUS_UNDER_REVIEW, $verification->status);
        $this->assertSame(1, $verification->attempt_number);
        $this->assertCount(3, $verification->files);
        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'cpf' => '12345678901',
        ]);
        $this->assertDatabaseHas('consents', [
            'user_id' => $user->id,
            'type' => 'terms_of_use',
            'version' => '1.0',
        ]);
        $this->assertDatabaseHas('consents', [
            'user_id' => $user->id,
            'type' => 'privacy_policy',
            'version' => '1.0',
        ]);

        foreach ($verification->files as $file) {
            Storage::disk('s3')->assertExists($file->path);
            $this->assertSame('private', Storage::disk('s3')->getVisibility($file->path));
        }

        Notification::assertSentTo(
            $user,
            VerificationStatusNotification::class,
            fn (VerificationStatusNotification $notification): bool => $notification->status === Verification::STATUS_UNDER_REVIEW
                && $notification->verification->is($verification),
        );
    }

    public function test_user_cannot_submit_an_incomplete_verification(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user)->post(route('app.verification.store'), [
            'full_name' => 'Pessoa Teste',
            'cpf' => '12345678901',
            'birth_date' => '1990-01-01',
            'phone' => '11999999999',
            'document_type' => 'rg',
            'document_front' => UploadedFile::fake()->image('front.jpg')->size(512),
            'document_back' => UploadedFile::fake()->image('back.jpg')->size(512),
            'accept_terms' => '1',
            'accept_privacy' => '1',
        ])->assertSessionHasErrors(['selfie']);

        $this->assertDatabaseCount('verifications', 0);
        Storage::disk('s3')->assertMissing('verifications/1/front.jpg');
    }

    public function test_each_verification_file_is_required(): void
    {
        foreach (['document_front', 'document_back', 'selfie'] as $missingFile) {
            $user = User::factory()->create();
            $user->assignRole('user');

            $payload = $this->validVerificationPayload();
            unset($payload[$missingFile]);

            $this->actingAs($user)
                ->post(route('app.verification.store'), $payload)
                ->assertSessionHasErrors([$missingFile]);

            $this->assertDatabaseMissing('verifications', [
                'user_id' => $user->id,
            ]);
        }
    }

    public function test_confirma_id_code_is_only_exposed_for_approved_verification(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        Verification::factory()->underReview()->create([
            'user_id' => $user->id,
            'attempt_number' => 1,
            'verification_code' => 'CID-111111',
        ]);

        $this->actingAs($user)->get(route('app.dashboard'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('app/dashboard')
                ->where('verification.verification_code', null)
            );

        $user->verifications()->delete();

        Verification::factory()->approved()->create([
            'user_id' => $user->id,
            'attempt_number' => 1,
            'verification_code' => 'CID-222222',
        ]);

        $this->actingAs($user)->get(route('app.dashboard'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('app/dashboard')
                ->where('verification.verification_code', 'CID-222222')
            );
    }

    /**
     * @return array<string, mixed>
     */
    private function validVerificationPayload(): array
    {
        return [
            'full_name' => 'Pessoa Teste',
            'cpf' => fake()->unique()->numerify('###########'),
            'birth_date' => '1990-01-01',
            'phone' => '11999999999',
            'document_type' => 'rg',
            'document_front' => UploadedFile::fake()->image('front.jpg')->size(512),
            'document_back' => UploadedFile::fake()->image('back.jpg')->size(512),
            'selfie' => UploadedFile::fake()->image('selfie.jpg')->size(512),
            'accept_terms' => '1',
            'accept_privacy' => '1',
        ];
    }
}
