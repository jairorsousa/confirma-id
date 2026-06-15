<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\PartnerQuery;
use App\Models\User;

class PartnerQueryPolicy
{
    public function view(User $user, PartnerQuery $partnerQuery): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin'])
            || $user->partnerMemberships()
                ->where('partner_id', $partnerQuery->partner_id)
                ->where('status', Partner::STATUS_ACTIVE)
                ->exists();
    }
}
