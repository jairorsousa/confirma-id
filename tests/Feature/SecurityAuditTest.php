<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\PartnerMember;
use App\Models\PartnerQuery;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Verification;
use App\Models\VerificationFile;
use App\Support\SensitiveData;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class SecurityAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        Notification::fake();
        Storage::fake('s3');
    }

    public function test_sensitive_data_helpers_mask_common_identifiers(): void
    {
        $this->assertSame('123.***.***-00', SensitiveData::cpf('12345678900'));
        $this->assertSame('pe***@***.com', SensitiveData::email('pessoa@example.com'));
        $this->assertSame('11*****9999', SensitiveData::phone('11999999999'));
    }

    public function test_verification_file_has_no_public_access_and_requires_admin(): void
    {
        Storage::disk('s3')->put('verifications/1/front.jpg', 'fake image');

        $verification = Verification::factory()->underReview()->create();
        $file = VerificationFile::factory()->front()->create([
            'verification_id' => $verification->id,
            'path' => 'verifications/1/front.jpg',
            'mime_type' => 'image/jpeg',
        ]);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $user = User::factory()->create();
        $user->assignRole('user');
        $partnerUser = User::factory()->create();
        $partnerUser->assignRole('partner');

        $this->get(route('admin.verification-files.show', $file))
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->get(route('admin.verification-files.show', $file))
            ->assertForbidden();

        $this->actingAs($partnerUser)
            ->get(route('admin.verification-files.show', $file))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.verification-files.show', $file))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_user_verification_submission_records_audit_logs_without_file_paths(): void
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
            'selfie' => UploadedFile::fake()->image('selfie.jpg')->size(512),
            'accept_terms' => '1',
            'accept_privacy' => '1',
        ])->assertRedirect(route('app.dashboard', absolute: false));

        $this->assertDatabaseHas('activity_log', [
            'event' => 'accepted',
            'description' => 'consent.accepted',
        ]);
        $this->assertDatabaseHas('activity_log', [
            'event' => 'documents_uploaded',
            'description' => 'verification.documents_uploaded',
        ]);

        $documentsLog = Activity::query()
            ->where('description', 'verification.documents_uploaded')
            ->firstOrFail();

        $this->assertStringNotContainsString('verifications/', $documentsLog->properties->toJson());
        $this->assertStringNotContainsString('12345678901', $documentsLog->properties->toJson());
    }

    public function test_partner_query_activity_log_contains_masked_term_only(): void
    {
        [$partnerUser, $partner] = $this->partnerUser(['can_query_cpf' => true]);
        $verifiedUser = User::factory()->create(['email' => 'joao@example.com']);
        UserProfile::factory()->create([
            'user_id' => $verifiedUser->id,
            'full_name' => 'Joao da Silva',
            'cpf' => '12345678900',
        ]);
        Verification::factory()->approved()->create([
            'user_id' => $verifiedUser->id,
            'verification_code' => 'CID-492817',
        ]);

        $token = $partnerUser->createToken('audit-token')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/partner/identity-query', [
                'query_type' => PartnerQuery::TYPE_CPF,
                'term' => '123.456.789-00',
            ])
            ->assertOk();

        $activity = Activity::query()
            ->where('description', 'partner.query')
            ->firstOrFail();

        $this->assertSame($partner->id, $activity->properties->get('partner_id'));
        $this->assertSame('123.***.***-00', $activity->properties->get('queried_term_masked'));
        $this->assertStringNotContainsString('12345678900', $activity->properties->toJson());
        $this->assertStringNotContainsString(hash('sha256', '12345678900'), $activity->properties->toJson());
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
}
