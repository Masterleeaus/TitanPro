<?php

namespace Modules\GroundZeroOps\Policies;

use App\Models\User;
use Modules\GroundZeroOps\Models\GroundZeroJob;

class GroundZeroJobPolicy
{
    public function view(User $user, GroundZeroJob $job): bool
    {
        return (int) ($user->company_id ?? 0) === (int) ($job->company_id ?? 0);
    }

    public function update(User $user, GroundZeroJob $job): bool
    {
        return $this->view($user, $job);
    }
}
