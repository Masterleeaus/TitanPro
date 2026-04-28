<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Estimate;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstimatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Estimate');
    }

    public function view(AuthUser $authUser, Estimate $estimate): bool
    {
        return $authUser->can('View:Estimate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Estimate');
    }

    public function update(AuthUser $authUser, Estimate $estimate): bool
    {
        return $authUser->can('Update:Estimate');
    }

    public function delete(AuthUser $authUser, Estimate $estimate): bool
    {
        return $authUser->can('Delete:Estimate');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Estimate');
    }

    public function restore(AuthUser $authUser, Estimate $estimate): bool
    {
        return $authUser->can('Restore:Estimate');
    }

    public function forceDelete(AuthUser $authUser, Estimate $estimate): bool
    {
        return $authUser->can('ForceDelete:Estimate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Estimate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Estimate');
    }

    public function replicate(AuthUser $authUser, Estimate $estimate): bool
    {
        return $authUser->can('Replicate:Estimate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Estimate');
    }

}