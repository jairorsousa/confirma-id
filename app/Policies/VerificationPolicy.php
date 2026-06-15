<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Verification;

class VerificationPolicy
{
    public function view(User $user, Verification $verification): bool
    {
        return $user->id === $verification->user_id || $user->hasAnyRole(['admin', 'super_admin']);
    }

    public function review(User $user, Verification $verification): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']) && $user->can('verification.review');
    }
}
