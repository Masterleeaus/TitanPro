<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EstimatePackage;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstimatePackagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EstimatePackage');
    }

    public function view(AuthUser $authUser, EstimatePackage $estimatePackage): bool
    {
        return $authUser->can('View:EstimatePackage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EstimatePackage');
    }

    public function update(AuthUser $authUser, EstimatePackage $estimatePackage): bool
    {
        return $authUser->can('Update:EstimatePackage');
    }

    public function delete(AuthUser $authUser, EstimatePackage $estimatePackage): bool
    {
        return $authUser->can('Delete:EstimatePackage');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:EstimatePackage');
    }

    public function restore(AuthUser $authUser, EstimatePackage $estimatePackage): bool
    {
        return $authUser->can('Restore:EstimatePackage');
    }

    public function forceDelete(AuthUser $authUser, EstimatePackage $estimatePackage): bool
    {
        return $authUser->can('ForceDelete:EstimatePackage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EstimatePackage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EstimatePackage');
    }

    public function replicate(AuthUser $authUser, EstimatePackage $estimatePackage): bool
    {
        return $authUser->can('Replicate:EstimatePackage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EstimatePackage');
    }

}