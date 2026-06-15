<?php

namespace Tests\Feature;

use App\Actions\Admin\ReviewVerification;
use App\Models\User;
use App\Models\Verification;
use App\Models\VerificationReview;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class AdminVerificationReviewTest extends TestCase
{
    use RefreshDatabase;

    private ReviewVerification $reviewVerification;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->reviewVerification = app(ReviewVerification::class);
    }

    public function test_admin_approves_verification_and_generates_unique_confirma_id_code(): void
    {
        $admin = $this->admin();
        $verification = Verification::factory()->underReview()->create();

        $reviewed = $this->reviewVerification->approve($verification, $admin, 'Documento conferido.');

        $this->assertSame(Verification::STATUS_APPROVED, $reviewed->status);
        $this->assertMatchesRegularExpression('/^CID-\d{6}$/', $reviewed->verification_code);
        $this->assertNotNull($reviewed->approved_at);
        $this->assertNotNull($reviewed->expires_at);
        $this->assertDatabaseHas('verification_reviews', [
            'verification_id' => $verification->id,
            'admin_id' => $admin->id,
            'decision' => VerificationReview::DECISION_APPROVED,
            'reason' => null,
            'notes' => 'Documento conferido.',
        ]);
        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $verification->id,
            'event' => VerificationReview::DECISION_APPROVED,
            'description' => 'verification.approved',
        ]);
    }

    public function test_rejecting_verification_requires_reason_and_records_history(): void
    {
        $admin = $this->admin();
        $verification = Verification::factory()->underReview()->create();

        try {
            $this->reviewVerification->reject($verification, $admin, '');
            $this->fail('Expected validation exception was not thrown.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('reason', $exception->errors());
        }

        $this->assertDatabaseMissing('verification_reviews', [
            'verification_id' => $verification->id,
            'decision' => VerificationReview::DECISION_REJECTED,
        ]);
    }

    public function test_admin_can_reject_request_correction_and_block_with_reason(): void
    {
        $admin = $this->admin();

        $rejected = $this->reviewVerification->reject(
            Verification::factory()->underReview()->create(),
            $admin,
            'Documento ilegivel.',
        );
        $correction = $this->reviewVerification->requestCorrection(
            Verification::factory()->underReview()->create(),
            $admin,
            'Envie selfie mais nitida.',
        );
        $blocked = $this->reviewVerification->block(
            Verification::factory()->underReview()->create(),
            $admin,
            'Suspeita de fraude.',
        );

        $this->assertSame(Verification::STATUS_REJECTED, $rejected->status);
        $this->assertSame(Verification::STATUS_CORRECTION_REQUESTED, $correction->status);
        $this->assertSame(Verification::STATUS_BLOCKED, $blocked->status);

        $this->assertDatabaseHas('verification_reviews', [
            'verification_id' => $rejected->id,
            'decision' => VerificationReview::DECISION_REJECTED,
            'reason' => 'Documento ilegivel.',
        ]);
        $this->assertDatabaseHas('verification_reviews', [
            'verification_id' => $correction->id,
            'decision' => VerificationReview::DECISION_CORRECTION_REQUESTED,
            'reason' => 'Envie selfie mais nitida.',
        ]);
        $this->assertDatabaseHas('verification_reviews', [
            'verification_id' => $blocked->id,
            'decision' => VerificationReview::DECISION_BLOCKED,
            'reason' => 'Suspeita de fraude.',
        ]);
        $this->assertSame(3, Activity::query()->count());
    }

    public function test_only_admin_profiles_can_access_filament_admin_panel(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        return $admin;
    }
}
