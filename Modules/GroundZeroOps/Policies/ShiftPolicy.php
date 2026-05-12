<?php

namespace Modules\GroundZeroOps\Policies;

use App\Models\User;
use Modules\GroundZeroOps\Models\Shift;

class ShiftPolicy
{
    public function view(User $user, Shift $shift): bool
    {
        return (int) ($user->company_id ?? 0) === (int) ($shift->company_id ?? 0);
    }

    public function update(User $user, Shift $shift): bool
    {
        return $this->view($user, $shift);
    }
}
