<?php

namespace Database\Seeders;

use App\Models\Consent;
use App\Models\Partner;
use App\Models\PartnerMember;
use App\Models\PartnerQuery;
use App\Models\User;
use App\Models\Verification;
use App\Models\VerificationFile;
use App\Models\VerificationReview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevelopmentMvpSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@confirmaid.local')->firstOrFail();
        $partnerUser = User::where('email', 'partner@confirmaid.local')->firstOrFail();
        $admin = User::where('email', 'admin@confirmaid.local')->firstOrFail();

        Consent::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => 'identity_verification',
                'version' => '1.0',
            ],
            [
                'accepted_at' => now(),
                'ip_address' => '127.0.0.1',
            ],
        );

        $verification = Verification::updateOrCreate(
            [
                'user_id' => $user->id,
                'attempt_number' => 1,
            ],
            [
                'document_type' => 'rg',
                'status' => Verification::STATUS_APPROVED,
                'verification_code' => 'CID-492817',
                'submitted_at' => now()->subDays(2),
                'approved_at' => now()->subDay(),
                'expires_at' => now()->addYear(),
            ],
        );

        foreach ([
            VerificationFile::TYPE_FRONT => 'front.jpg',
            VerificationFile::TYPE_BACK => 'back.jpg',
            VerificationFile::TYPE_SELFIE => 'selfie.jpg',
        ] as $fileType => $filename) {
            VerificationFile::updateOrCreate(
                [
                    'verification_id' => $verification->id,
                    'file_type' => $fileType,
                ],
                [
                    'disk' => 's3',
                    'path' => "verifications/{$verification->id}/{$filename}",
                    'mime_type' => 'image/jpeg',
                    'size_bytes' => 512_000,
                    'uploaded_at' => now()->subDays(2),
                ],
            );
        }

        VerificationReview::updateOrCreate(
            [
                'verification_id' => $verification->id,
                'decision' => VerificationReview::DECISION_APPROVED,
            ],
            [
                'admin_id' => $admin->id,
                'reason' => 'Documentos legiveis e dados conferidos.',
                'notes' => 'Registro de desenvolvimento para o MVP.',
                'decided_at' => now()->subDay(),
            ],
        );

        $partner = Partner::updateOrCreate(
            ['cnpj' => '12345678000190'],
            [
                'legal_name' => 'Parceiro Demo Ltda',
                'trade_name' => 'Parceiro Demo',
                'responsible_name' => 'Responsavel Demo',
                'email' => 'contato@parceiro.local',
                'phone' => '1133334444',
                'status' => Partner::STATUS_ACTIVE,
                'api_key_hash' => Hash::make('dev-partner-key'),
            ],
        );

        PartnerMember::updateOrCreate(
            [
                'partner_id' => $partner->id,
                'user_id' => $partnerUser->id,
            ],
            [
                'role' => PartnerMember::ROLE_OWNER,
                'status' => 'active',
            ],
        );

        PartnerQuery::updateOrCreate(
            [
                'partner_id' => $partner->id,
                'queried_term_hash' => hash('sha256', 'CID-492817'),
            ],
            [
                'user_id' => $user->id,
                'query_type' => PartnerQuery::TYPE_CODE,
                'queried_term_masked' => 'CID-492817',
                'result' => PartnerQuery::RESULT_APPROVED,
                'ip_address' => '127.0.0.1',
                'origin' => 'seed',
                'credential_label' => 'dev-partner-key',
            ],
        );
    }
}
