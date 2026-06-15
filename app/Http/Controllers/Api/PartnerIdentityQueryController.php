<?php

namespace App\Http\Controllers\Api;

use App\Actions\Partner\QueryIdentity;
use App\Actions\Partner\ResolvePartnerForUser;
use App\Http\Controllers\Controller;
use App\Models\PartnerQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartnerIdentityQueryController extends Controller
{
    public function __invoke(Request $request, ResolvePartnerForUser $resolver, QueryIdentity $queryIdentity): JsonResponse
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
            throw new AuthorizationException('Nenhum parceiro ativo encontrado para este token.');
        }

        $token = $request->user()->currentAccessToken();

        return response()->json($queryIdentity->handle(
            partner: $membership->partner,
            queryType: $validated['query_type'],
            term: $validated['term'],
            ipAddress: $request->ip(),
            origin: 'api',
            credentialLabel: $token?->name,
        ));
    }
}
