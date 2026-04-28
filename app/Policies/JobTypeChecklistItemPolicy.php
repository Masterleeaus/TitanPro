<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JobTypeChecklistItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobTypeChecklistItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JobTypeChecklistItem');
    }

    public function view(AuthUser $authUser, JobTypeChecklistItem $jobTypeChecklistItem): bool
    {
        return $authUser->can('View:JobTypeChecklistItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JobTypeChecklistItem');
    }

    public function update(AuthUser $authUser, JobTypeChecklistItem $jobTypeChecklistItem): bool
    {
        return $authUser->can('Update:JobTypeChecklistItem');
    }

    public function delete(AuthUser $authUser, JobTypeChecklistItem $jobTypeChecklistItem): bool
    {
        return $authUser->can('Delete:JobTypeChecklistItem');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:JobTypeChecklistItem');
    }

    public function restore(AuthUser $authUser, JobTypeChecklistItem $jobTypeChecklistItem): bool
    {
        return $authUser->can('Restore:JobTypeChecklistItem');
    }

    public function forceDelete(AuthUser $authUser, JobTypeChecklistItem $jobTypeChecklistItem): bool
    {
        return $authUser->can('ForceDelete:JobTypeChecklistItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JobTypeChecklistItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JobTypeChecklistItem');
    }

    public function replicate(AuthUser $authUser, JobTypeChecklistItem $jobTypeChecklistItem): bool
    {
        return $authUser->can('Replicate:JobTypeChecklistItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JobTypeChecklistItem');
    }

}