<?php

namespace Modules\CRMCore\Policies;

use App\Models\User;

class CRMCorePolicy
{
    public function view(User $user): bool
    {
        return true;
    }

    public function manage(User $user): bool
    {
        return true;
    }
}
