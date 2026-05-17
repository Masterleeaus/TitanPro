<?php

namespace Modules\GroundZeroOps\Policies;

use App\Models\User;
use Modules\GroundZeroOps\Models\Incident;

class IncidentPolicy
{
    public function view(User $user, Incident $incident): bool
    {
        return (int) ($user->company_id ?? 0) === (int) ($incident->company_id ?? 0);
    }

    public function create(User $user): bool
    {
        return (int) ($user->company_id ?? 0) > 0;
    }
}
