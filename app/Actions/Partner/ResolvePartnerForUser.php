<?php

namespace App\Actions\Partner;

use App\Models\Partner;
use App\Models\PartnerMember;
use App\Models\User;

class ResolvePartnerForUser
{
    public function activeMembership(User $user): ?PartnerMember
    {
        return $user->partnerMemberships()
            ->with('partner')
            ->where('status', Partner::STATUS_ACTIVE)
            ->whereHas('partner', fn ($query) => $query->where('status', Partner::STATUS_ACTIVE))
            ->oldest()
            ->first();
    }

    public function firstMembership(User $user): ?PartnerMember
    {
        return $user->partnerMemberships()
            ->with('partner')
            ->oldest()
            ->first();
    }
}
