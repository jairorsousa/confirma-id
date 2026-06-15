<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;

class PartnerPolicy
{
    public function view(User $user, Partner $partner): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin'])
            || $user->partnerMemberships()
                ->where('partner_id', $partner->id)
                ->where('status', Partner::STATUS_ACTIVE)
                ->exists();
    }

    public function update(User $user, Partner $partner): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']) && $user->can('partner.manage');
    }

    public function query(User $user, Partner $partner): bool
    {
        return $partner->status === Partner::STATUS_ACTIVE
            && $user->partnerMemberships()
                ->where('partner_id', $partner->id)
                ->where('status', Partner::STATUS_ACTIVE)
                ->exists();
    }
}
