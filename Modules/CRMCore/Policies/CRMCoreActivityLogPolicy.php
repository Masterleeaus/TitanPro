<?php

declare(strict_types=1);

namespace Modules\CRMCore\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\CRMCore\Models\CRMCoreActivityLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class CRMCoreActivityLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CRMCoreActivityLog');
    }

    public function view(AuthUser $authUser, CRMCoreActivityLog $cRMCoreActivityLog): bool
    {
        if ((int) ($cRMCoreActivityLog->company_id ?? 0) !== (int) ($authUser->company_id ?? $authUser->organization_id ?? 0)) {
            return false;
        }

        return $authUser->can('View:CRMCoreActivityLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CRMCoreActivityLog');
    }

    public function update(AuthUser $authUser, CRMCoreActivityLog $cRMCoreActivityLog): bool
    {
        if ((int) ($cRMCoreActivityLog->company_id ?? 0) !== (int) ($authUser->company_id ?? $authUser->organization_id ?? 0)) {
            return false;
        }

        return $authUser->can('Update:CRMCoreActivityLog');
    }

    public function delete(AuthUser $authUser, CRMCoreActivityLog $cRMCoreActivityLog): bool
    {
        if ((int) ($cRMCoreActivityLog->company_id ?? 0) !== (int) ($authUser->company_id ?? $authUser->organization_id ?? 0)) {
            return false;
        }

        return $authUser->can('Delete:CRMCoreActivityLog');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CRMCoreActivityLog');
    }

    public function restore(AuthUser $authUser, CRMCoreActivityLog $cRMCoreActivityLog): bool
    {
        if ((int) ($cRMCoreActivityLog->company_id ?? 0) !== (int) ($authUser->company_id ?? $authUser->organization_id ?? 0)) {
            return false;
        }

        return $authUser->can('Restore:CRMCoreActivityLog');
    }

    public function forceDelete(AuthUser $authUser, CRMCoreActivityLog $cRMCoreActivityLog): bool
    {
        if ((int) ($cRMCoreActivityLog->company_id ?? 0) !== (int) ($authUser->company_id ?? $authUser->organization_id ?? 0)) {
            return false;
        }

        return $authUser->can('ForceDelete:CRMCoreActivityLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CRMCoreActivityLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CRMCoreActivityLog');
    }

    public function replicate(AuthUser $authUser, CRMCoreActivityLog $cRMCoreActivityLog): bool
    {
        if ((int) ($cRMCoreActivityLog->company_id ?? 0) !== (int) ($authUser->company_id ?? $authUser->organization_id ?? 0)) {
            return false;
        }

        return $authUser->can('Replicate:CRMCoreActivityLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CRMCoreActivityLog');
    }

}
