<?php

namespace Tests\Feature;

use App\Models\PartnerQuery;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Verification;
use App\Models\VerificationFile;
use App\Models\VerificationReview;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MvpDataModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_cpf_must_be_unique(): void
    {
        UserProfile::factory()->create(['cpf' => '12345678900']);

        $this->expectException(QueryException::class);

        UserProfile::factory()->create(['cpf' => '12345678900']);
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'same@example.com']);

        $this->expectException(QueryException::class);

        User::factory()->create(['email' => 'same@example.com']);
    }

    public function test_confirma_id_code_must_be_unique(): void
    {
        Verification::factory()->approved()->create(['verification_code' => 'CID-000001']);

        $this->expectException(QueryException::class);

        Verification::factory()->approved()->create(['verification_code' => 'CID-000001']);
    }

    public function test_resubmission_keeps_previous_files_and_review_history(): void
    {
        $user = User::factory()->create();

        $firstAttempt = Verification::factory()->create([
            'user_id' => $user->id,
            'attempt_number' => 1,
            'status' => Verification::STATUS_REJECTED,
        ]);
        VerificationFile::factory()->front()->create(['verification_id' => $firstAttempt->id]);
        VerificationReview::factory()->create([
            'verification_id' => $firstAttempt->id,
            'decision' => VerificationReview::DECISION_REJECTED,
        ]);

        $secondAttempt = Verification::factory()->underReview()->create([
            'user_id' => $user->id,
            'attempt_number' => 2,
        ]);

        $this->assertCount(2, $user->verifications()->get());
        $this->assertSame($firstAttempt->id, $user->verifications()->oldest('attempt_number')->first()->id);
        $this->assertCount(1, $firstAttempt->files);
        $this->assertCount(1, $firstAttempt->reviews);
        $this->assertSame(Verification::STATUS_UNDER_REVIEW, $secondAttempt->status);
    }

    public function test_partner_query_keeps_hash_and_masked_term_only(): void
    {
        $rawTerm = 'pessoa@example.com';
        $hash = hash('sha256', $rawTerm);

        $query = PartnerQuery::factory()->create([
            'query_type' => PartnerQuery::TYPE_EMAIL,
            'queried_term_hash' => $hash,
            'queried_term_masked' => 'pe***@***',
        ]);

        $this->assertSame($hash, $query->queried_term_hash);
        $this->assertNotSame($rawTerm, $query->queried_term_masked);
        $this->assertDatabaseHas('partner_queries', [
            'id' => $query->id,
            'queried_term_hash' => $hash,
            'queried_term_masked' => 'pe***@***',
        ]);
    }
}
