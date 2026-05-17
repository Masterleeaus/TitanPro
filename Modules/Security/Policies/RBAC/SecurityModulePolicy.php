<?php

namespace Modules\Security\Policies\RBAC;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Security\Support\Constants\SecurityPermissions;

class SecurityModulePolicy
{
    public function view(Authenticatable $user): bool
    {
        return $this->allows($user, SecurityPermissions::VIEW);
    }

    public function approve(Authenticatable $user): bool
    {
        return $this->allows($user, SecurityPermissions::APPROVE);
    }

    public function validateRecord(Authenticatable $user): bool
    {
        return $this->allows($user, SecurityPermissions::VALIDATE);
    }

    public function diagnostics(Authenticatable $user): bool
    {
        return $this->allows($user, SecurityPermissions::DIAGNOSTICS);
    }

    private function allows(Authenticatable $user, string $permission): bool
    {
        return method_exists($user, 'can') ? (bool) $user->can($permission) : false;
    }
}
