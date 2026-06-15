<?php

namespace App\Actions\Partner;

use App\Models\Partner;
use App\Models\PartnerQuery;
use App\Models\User;
use App\Models\Verification;
use App\Support\SensitiveData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class QueryIdentity
{
    /**
     * @return array<string, mixed>
     */
    public function handle(
        Partner $partner,
        string $queryType,
        string $term,
        ?string $ipAddress = null,
        string $origin = 'web',
        ?string $credentialLabel = null,
        ?User $actor = null,
    ): array {
        if ($partner->status !== Partner::STATUS_ACTIVE) {
            throw new AuthorizationException('Parceiro inativo nao pode consultar identidades.');
        }

        $normalizedTerm = $this->normalizeTerm($queryType, $term);

        if ($queryType === PartnerQuery::TYPE_CPF && ! $partner->can_query_cpf) {
            $this->recordQuery(
                partner: $partner,
                user: null,
                queryType: $queryType,
                normalizedTerm: $normalizedTerm,
                maskedTerm: $this->maskTerm($queryType, $normalizedTerm),
                result: PartnerQuery::RESULT_BLOCKED,
                ipAddress: $ipAddress,
                origin: $origin,
                credentialLabel: $credentialLabel,
                actor: $actor,
            );

            throw new AuthorizationException('Parceiro sem permissao para consulta por CPF.');
        }

        $verification = $this->findVerification($queryType, $normalizedTerm);
        $user = $verification?->user;

        $result = $this->resultFor($verification);

        $this->recordQuery(
            partner: $partner,
            user: $user,
            queryType: $queryType,
            normalizedTerm: $normalizedTerm,
            maskedTerm: $this->maskTerm($queryType, $normalizedTerm),
            result: $result,
            ipAddress: $ipAddress,
            origin: $origin,
            credentialLabel: $credentialLabel,
            actor: $actor,
        );

        return $this->safePayload($user, $verification, $result);
    }

    private function findVerification(string $queryType, string $normalizedTerm): ?Verification
    {
        return match ($queryType) {
            PartnerQuery::TYPE_CODE => Verification::query()
                ->with(['user.profile'])
                ->where('verification_code', mb_strtoupper($normalizedTerm))
                ->first(),
            PartnerQuery::TYPE_EMAIL => $this->latestVerificationForUser(User::query()
                ->whereRaw('lower(email) = ?', [mb_strtolower($normalizedTerm)])
                ->first()),
            PartnerQuery::TYPE_CPF => $this->latestVerificationForUser(User::query()
                ->whereHas('profile', fn ($query) => $query->where('cpf', $normalizedTerm))
                ->first()),
            default => null,
        };
    }

    private function latestVerificationForUser(?User $user): ?Verification
    {
        return $user?->verifications()
            ->with(['user.profile'])
            ->latest('attempt_number')
            ->latest()
            ->first();
    }

    private function resultFor(?Verification $verification): string
    {
        if (! $verification) {
            return PartnerQuery::RESULT_NOT_FOUND;
        }

        return match ($verification->status) {
            Verification::STATUS_APPROVED => $verification->expires_at && $verification->expires_at->isPast()
                ? PartnerQuery::RESULT_NOT_FOUND
                : PartnerQuery::RESULT_APPROVED,
            Verification::STATUS_BLOCKED => PartnerQuery::RESULT_BLOCKED,
            Verification::STATUS_PENDING,
            Verification::STATUS_UNDER_REVIEW,
            Verification::STATUS_CORRECTION_REQUESTED => PartnerQuery::RESULT_UNDER_REVIEW,
            default => PartnerQuery::RESULT_NOT_FOUND,
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function safePayload(?User $user, ?Verification $verification, string $result): array
    {
        $verified = $result === PartnerQuery::RESULT_APPROVED;

        return [
            'verified' => $verified,
            'status' => $result,
            'verification_code' => $verified ? $verification?->verification_code : null,
            'name' => $user?->profile?->full_name ?: $user?->name,
            'document_masked' => $user ? SensitiveData::cpf($user->profile?->cpf, '') : null,
            'verified_at' => $verified ? $verification?->approved_at?->toDateString() : null,
            'expires_at' => $verified ? $verification?->expires_at?->toDateString() : null,
        ];
    }

    private function recordQuery(
        Partner $partner,
        ?User $user,
        string $queryType,
        string $normalizedTerm,
        ?string $maskedTerm,
        string $result,
        ?string $ipAddress,
        string $origin,
        ?string $credentialLabel,
        ?User $actor,
    ): void {
        DB::transaction(function () use ($partner, $user, $queryType, $normalizedTerm, $maskedTerm, $result, $ipAddress, $origin, $credentialLabel, $actor): void {
            $query = $partner->queries()->create([
                'user_id' => $user?->id,
                'query_type' => $queryType,
                'queried_term_hash' => hash('sha256', $normalizedTerm),
                'queried_term_masked' => $maskedTerm,
                'result' => $result,
                'ip_address' => $ipAddress,
                'origin' => $origin,
                'credential_label' => $credentialLabel,
            ]);

            activity()
                ->performedOn($query)
                ->causedBy($actor)
                ->event('queried')
                ->withProperties([
                    'partner_id' => $partner->id,
                    'query_type' => $queryType,
                    'queried_term_masked' => $maskedTerm,
                    'result' => $result,
                    'origin' => $origin,
                    'credential_label' => $credentialLabel,
                ])
                ->log('partner.query');
        });
    }

    private function normalizeTerm(string $queryType, string $term): string
    {
        $term = trim($term);

        return match ($queryType) {
            PartnerQuery::TYPE_EMAIL => mb_strtolower($term),
            PartnerQuery::TYPE_CPF => preg_replace('/\D/', '', $term),
            PartnerQuery::TYPE_CODE => mb_strtoupper($term),
            default => $term,
        };
    }

    private function maskTerm(string $queryType, string $normalizedTerm): ?string
    {
        return match ($queryType) {
            PartnerQuery::TYPE_EMAIL => SensitiveData::email($normalizedTerm, ''),
            PartnerQuery::TYPE_CPF => SensitiveData::cpf($normalizedTerm, ''),
            PartnerQuery::TYPE_CODE => $normalizedTerm,
            default => null,
        };
    }
}
