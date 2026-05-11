<?php

namespace App\Extensions\TitanOperator\System\Policies;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Models\User;

class TitanOperatorPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TitanOperator $titan_operator): bool
    {
        return $user->id === $titan_operator->user_id;
    }

    public function train(User $user, TitanOperator $titan_operator): bool
    {
        return $user->id === $titan_operator->user_id;
    }

    public function update(User $user, TitanOperator $titan_operator): bool
    {
        return $user->id === $titan_operator->user_id;
    }

    public function delete(User $user, TitanOperator $titan_operator): bool
    {
        return $user->id === $titan_operator->user_id;
    }
}
