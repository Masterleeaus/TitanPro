<?php

declare(strict_types=1);

namespace Modules\CRMCore\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\CRMCore\Models\Lead;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Lead');
    }

    public function view(AuthUser $authUser, Lead $lead): bool
    {
        if (! $this->belongsToOrganization($authUser, $lead)) {
            return false;
        }

        return $authUser->can('View:Lead');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Lead');
    }

    public function update(AuthUser $authUser, Lead $lead): bool
    {
        if (! $this->belongsToOrganization($authUser, $lead)) {
            return false;
        }

        return $authUser->can('Update:Lead');
    }

    public function delete(AuthUser $authUser, Lead $lead): bool
    {
        if (! $this->belongsToOrganization($authUser, $lead)) {
            return false;
        }

        return $authUser->can('Delete:Lead');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Lead');
    }

    public function restore(AuthUser $authUser, Lead $lead): bool
    {
        if (! $this->belongsToOrganization($authUser, $lead)) {
            return false;
        }

        return $authUser->can('Restore:Lead');
    }

    public function forceDelete(AuthUser $authUser, Lead $lead): bool
    {
        if (! $this->belongsToOrganization($authUser, $lead)) {
            return false;
        }

        return $authUser->can('ForceDelete:Lead');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Lead');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Lead');
    }

    public function replicate(AuthUser $authUser, Lead $lead): bool
    {
        if (! $this->belongsToOrganization($authUser, $lead)) {
            return false;
        }

        return $authUser->can('Replicate:Lead');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Lead');
    }

    private function belongsToOrganization(AuthUser $authUser, Lead $lead): bool
    {
        if ($lead->crmcore_customer_id !== null) {
            return (int) ($lead->crmcoreCustomer?->organization_id ?? 0) === (int) $authUser->organization_id;
        }

        return true;
    }

}
