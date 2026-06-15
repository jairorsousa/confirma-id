<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VerificationFile;

class VerificationFilePolicy
{
    public function view(User $user, VerificationFile $verificationFile): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']);
    }
}
