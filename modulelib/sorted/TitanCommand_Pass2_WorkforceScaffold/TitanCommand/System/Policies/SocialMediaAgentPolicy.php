<?php

namespace App\Extensions\TitanCommand\System\Policies;

use App\Extensions\TitanCommand\System\Models\TitanCommand;
use App\Models\User;

class TitanCommandPolicy
{
    /**
     * Determine whether the user can view any agents.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the agent.
     */
    public function view(User $user, TitanCommand $agent): bool
    {
        return $user->id === $agent->user_id;
    }

    /**
     * Determine whether the user can create agents.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the agent.
     */
    public function update(User $user, TitanCommand $agent): bool
    {
        return $user->id === $agent->user_id;
    }

    /**
     * Determine whether the user can delete the agent.
     */
    public function delete(User $user, TitanCommand $agent): bool
    {
        return $user->id === $agent->user_id;
    }

    /**
     * Determine whether the user can restore the agent.
     */
    public function restore(User $user, TitanCommand $agent): bool
    {
        return $user->id === $agent->user_id;
    }

    /**
     * Determine whether the user can permanently delete the agent.
     */
    public function forceDelete(User $user, TitanCommand $agent): bool
    {
        return $user->id === $agent->user_id;
    }
}
