<?php

namespace App\Http\Controllers\Partner;

use App\Actions\Partner\QueryIdentity;
use App\Actions\Partner\ResolvePartnerForUser;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PartnerDashboardController extends Controller
{
    public function show(Request $request, ResolvePartnerForUser $resolver): Response
    {
        $membership = $resolver->firstMembership($request->user());
        $partner = $membership?->partner;

        if ($partner) {
            $this->authorize('view', $partner);
        }

        return Inertia::render('partner/dashboard', [
            'partner' => $partner ? $this->partnerPayload($partner) : null,
            'stats' => $partner ? $this->statsPayload($partner) : $this->emptyStats(),
            'recent_queries' => $partner ? $this->recentQueriesPayload($partner) : [],
            'can_query' => $partner?->status === Partner::STATUS_ACTIVE && $membership?->status === Partner::STATUS_ACTIVE,
        ]);
    }

    public function query(Request $request, ResolvePartnerForUser $resolver, QueryIdentity $queryIdentity): RedirectResponse
    {
        $validated = $request->validate([
            'query_type' => ['required', Rule::in([
                PartnerQuery::TYPE_CODE,
                PartnerQuery::TYPE_EMAIL,
                PartnerQuery::TYPE_CPF,
            ])],
            'term' => ['required', 'string', 'max:255'],
        ]);

        $membership = $resolver->activeMembership($request->user());

        if (! $membership) {
            throw new AuthorizationException('Nenhum parceiro ativo encontrado para este usuario.');
        }

        $this->authorize('query', $membership->partner);

        try {
            $result = $queryIdentity->handle(
                partner: $membership->partner,
                queryType: $validated['query_type'],
                term: $validated['term'],
                ipAddress: $request->ip(),
                origin: 'web',
                credentialLabel: $request->user()->email,
                actor: $request->user(),
            );
        } catch (AuthorizationException $exception) {
            return back()
                ->withErrors(['term' => $exception->getMessage()])
                ->withInput();
        }

        return back()->with('partner_query_result', $result);
    }

    /**
     * @return array<string, mixed>
     */
    private function partnerPayload(Partner $partner): array
    {
        return [
            'id' => $partner->id,
            'legal_name' => $partner->legal_name,
            'trade_name' => $partner->trade_name,
            'status' => $partner->status,
            'plan_name' => $partner->plan_name,
            'can_query_cpf' => $partner->can_query_cpf,
        ];
    }

    /**
     * @return array<string, int>
     */
    private function statsPayload(Partner $partner): array
    {
        return [
            'total_queries' => $partner->queries()->count(),
            'verified_users' => $partner->queries()
                ->where('result', PartnerQuery::RESULT_APPROVED)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id'),
            'monthly_queries' => $partner->queries()
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
        ];
    }

    /**
     * @return array<string, int>
     */
    private function emptyStats(): array
    {
        return [
            'total_queries' => 0,
            'verified_users' => 0,
            'monthly_queries' => 0,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function recentQueriesPayload(Partner $partner): array
    {
        return $partner->queries()
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (PartnerQuery $query): array => [
                'id' => $query->id,
                'query_type' => $query->query_type,
                'queried_term_masked' => $query->queried_term_masked,
                'result' => $query->result,
                'origin' => $query->origin,
                'created_at' => $query->created_at?->toISOString(),
            ])
            ->all();
    }
}
