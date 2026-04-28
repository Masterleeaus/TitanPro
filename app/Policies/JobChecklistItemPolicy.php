<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JobChecklistItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobChecklistItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JobChecklistItem');
    }

    public function view(AuthUser $authUser, JobChecklistItem $jobChecklistItem): bool
    {
        return $authUser->can('View:JobChecklistItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JobChecklistItem');
    }

    public function update(AuthUser $authUser, JobChecklistItem $jobChecklistItem): bool
    {
        return $authUser->can('Update:JobChecklistItem');
    }

    public function delete(AuthUser $authUser, JobChecklistItem $jobChecklistItem): bool
    {
        return $authUser->can('Delete:JobChecklistItem');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:JobChecklistItem');
    }

    public function restore(AuthUser $authUser, JobChecklistItem $jobChecklistItem): bool
    {
        return $authUser->can('Restore:JobChecklistItem');
    }

    public function forceDelete(AuthUser $authUser, JobChecklistItem $jobChecklistItem): bool
    {
        return $authUser->can('ForceDelete:JobChecklistItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JobChecklistItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JobChecklistItem');
    }

    public function replicate(AuthUser $authUser, JobChecklistItem $jobChecklistItem): bool
    {
        return $authUser->can('Replicate:JobChecklistItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JobChecklistItem');
    }

}