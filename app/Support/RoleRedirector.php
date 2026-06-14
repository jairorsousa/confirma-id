<?php

namespace App\Support;

use App\Models\User;

class RoleRedirector
{
    public static function pathFor(User $user): string
    {
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return route('admin.dashboard', absolute: false);
        }

        if ($user->hasRole('partner')) {
            return route('partner.dashboard', absolute: false);
        }

        return route('app.dashboard', absolute: false);
    }
}
