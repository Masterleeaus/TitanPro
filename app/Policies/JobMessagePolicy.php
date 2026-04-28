<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JobMessage;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobMessagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JobMessage');
    }

    public function view(AuthUser $authUser, JobMessage $jobMessage): bool
    {
        return $authUser->can('View:JobMessage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JobMessage');
    }

    public function update(AuthUser $authUser, JobMessage $jobMessage): bool
    {
        return $authUser->can('Update:JobMessage');
    }

    public function delete(AuthUser $authUser, JobMessage $jobMessage): bool
    {
        return $authUser->can('Delete:JobMessage');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:JobMessage');
    }

    public function restore(AuthUser $authUser, JobMessage $jobMessage): bool
    {
        return $authUser->can('Restore:JobMessage');
    }

    public function forceDelete(AuthUser $authUser, JobMessage $jobMessage): bool
    {
        return $authUser->can('ForceDelete:JobMessage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JobMessage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JobMessage');
    }

    public function replicate(AuthUser $authUser, JobMessage $jobMessage): bool
    {
        return $authUser->can('Replicate:JobMessage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JobMessage');
    }

}