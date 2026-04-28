<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DriverLocation;
use Illuminate\Auth\Access\HandlesAuthorization;

class DriverLocationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DriverLocation');
    }

    public function view(AuthUser $authUser, DriverLocation $driverLocation): bool
    {
        return $authUser->can('View:DriverLocation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DriverLocation');
    }

    public function update(AuthUser $authUser, DriverLocation $driverLocation): bool
    {
        return $authUser->can('Update:DriverLocation');
    }

    public function delete(AuthUser $authUser, DriverLocation $driverLocation): bool
    {
        return $authUser->can('Delete:DriverLocation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DriverLocation');
    }

    public function restore(AuthUser $authUser, DriverLocation $driverLocation): bool
    {
        return $authUser->can('Restore:DriverLocation');
    }

    public function forceDelete(AuthUser $authUser, DriverLocation $driverLocation): bool
    {
        return $authUser->can('ForceDelete:DriverLocation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DriverLocation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DriverLocation');
    }

    public function replicate(AuthUser $authUser, DriverLocation $driverLocation): bool
    {
        return $authUser->can('Replicate:DriverLocation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DriverLocation');
    }

}