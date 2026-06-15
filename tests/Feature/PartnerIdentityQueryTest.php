<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\PartnerMember;
use App\Models\PartnerQuery;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Verification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerIdentityQueryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_partner_can_query_approved_identity_by_confirma_id_code(): void
    {
        [$partnerUser, $partner] = $this->partnerUser();
        [$verifiedUser, $verification] = $this->approvedIdentity();

        $this->actingAs($partnerUser)
            ->from('/partner')
            ->post('/partner/query', [
                'query_type' => PartnerQuery::TYPE_CODE,
                'term' => $verification->verification_code,
            ])
            ->assertRedirect('/partner')
            ->assertSessionHas('partner_query_result.verified', true)
            ->assertSessionHas('partner_query_result.document_masked', '123.***.***-00');

        $this->assertDatabaseHas('partner_queries', [
            'partner_id' => $partner->id,
            'user_id' => $verifiedUser->id,
            'query_type' => PartnerQuery::TYPE_CODE,
            'queried_term_masked' => $verification->verification_code,
            'result' => PartnerQuery::RESULT_APPROVED,
            'origin' => 'web',
        ]);
    }

    public function test_partner_api_returns_safe_payload_and_records_history(): void
    {
        [$partnerUser, $partner] = $this->partnerUser(['can_query_cpf' => true]);
        [$verifiedUser] = $this->approvedIdentity();
        $token = $partnerUser->createToken('partner-default')->plainTextToken;

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/partner/identity-query', [
                'query_type' => PartnerQuery::TYPE_CPF,
                'term' => '123.456.789-00',
            ])
            ->assertOk()
            ->assertJson([
                'verified' => true,
                'status' => PartnerQuery::RESULT_APPROVED,
                'name' => 'Joao da Silva',
                'document_masked' => '123.***.***-00',
            ]);

        $response->assertJsonMissingPath('cpf');
        $response->assertJsonMissingPath('document_front');
        $response->assertJsonMissingPath('selfie');

        $this->assertDatabaseHas('partner_queries', [
            'partner_id' => $partner->id,
            'user_id' => $verifiedUser->id,
            'query_type' => PartnerQuery::TYPE_CPF,
            'queried_term_masked' => '123.***.***-00',
            'result' => PartnerQuery::RESULT_APPROVED,
            'origin' => 'api',
            'credential_label' => 'partner-default',
        ]);
    }

    public function test_inactive_partner_cannot_query(): void
    {
        [$partnerUser] = $this->partnerUser(['status' => Partner::STATUS_INACTIVE]);
        $token = $partnerUser->createToken('inactive-token')->plainTextToken;

        $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/partner/identity-query', [
                'query_type' => PartnerQuery::TYPE_EMAIL,
                'term' => 'person@example.com',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('partner_queries', 0);
    }

    public function test_partner_without_cpf_permission_cannot_query_by_cpf_but_attempt_is_recorded(): void
    {
        [$partnerUser, $partner] = $this->partnerUser(['can_query_cpf' => false]);
        $token = $partnerUser->createToken('limited-token')->plainTextToken;

        $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/partner/identity-query', [
                'query_type' => PartnerQuery::TYPE_CPF,
                'term' => '123.456.789-00',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('partner_queries', [
            'partner_id' => $partner->id,
            'user_id' => null,
            'query_type' => PartnerQuery::TYPE_CPF,
            'queried_term_masked' => '123.***.***-00',
            'result' => PartnerQuery::RESULT_BLOCKED,
            'origin' => 'api',
        ]);
    }

    public function test_partner_dashboard_shows_stats_and_recent_history(): void
    {
        [$partnerUser, $partner] = $this->partnerUser();
        PartnerQuery::factory()->create([
            'partner_id' => $partner->id,
            'result' => PartnerQuery::RESULT_APPROVED,
            'created_at' => now(),
        ]);

        $this->actingAs($partnerUser)
            ->get('/partner')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('partner/dashboard')
                ->where('partner.id', $partner->id)
                ->where('stats.total_queries', 1)
                ->has('recent_queries', 1)
            );
    }

    /**
     * @param  array<string, mixed>  $partnerAttributes
     * @return array{0: User, 1: Partner}
     */
    private function partnerUser(array $partnerAttributes = []): array
    {
        $user = User::factory()->create();
        $user->assignRole('partner');
        $partner = Partner::factory()->create($partnerAttributes);

        PartnerMember::factory()->create([
            'partner_id' => $partner->id,
            'user_id' => $user->id,
            'role' => PartnerMember::ROLE_OWNER,
            'status' => Partner::STATUS_ACTIVE,
        ]);

        return [$user, $partner];
    }

    /**
     * @return array{0: User, 1: Verification}
     */
    private function approvedIdentity(): array
    {
        $user = User::factory()->create([
            'name' => 'Joao da Silva',
            'email' => 'joao@example.com',
        ]);
        UserProfile::factory()->create([
            'user_id' => $user->id,
            'full_name' => 'Joao da Silva',
            'cpf' => '12345678900',
        ]);
        $verification = Verification::factory()->approved()->create([
            'user_id' => $user->id,
            'verification_code' => 'CID-492817',
            'approved_at' => now()->subDay(),
            'expires_at' => now()->addYear(),
        ]);

        return [$user, $verification];
    }
}
