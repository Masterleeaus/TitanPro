<?php

namespace Modules\TitanProAdmin\Policies;

use Illuminate\Contracts\Auth\Authenticatable;

class SuperAdminPolicy
{
    public function access(?Authenticatable $user): bool
    {
        return (bool) $user && method_exists($user, 'hasRole') && $user->hasRole('super_admin');
    }
}
