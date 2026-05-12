<?php

namespace Modules\GroundZeroOps\Policies;

use App\Models\User;
use Modules\GroundZeroOps\Models\Dispatch;

class DispatchPolicy
{
    public function view(User $user, Dispatch $dispatch): bool
    {
        return (int) ($user->company_id ?? 0) === (int) ($dispatch->company_id ?? 0);
    }

    public function create(User $user): bool
    {
        return (int) ($user->company_id ?? 0) > 0;
    }
}
