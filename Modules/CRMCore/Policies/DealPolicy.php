<?php

declare(strict_types=1);

namespace Modules\CRMCore\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\CRMCore\Models\Deal;
use Illuminate\Auth\Access\HandlesAuthorization;

class DealPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Deal');
    }

    public function view(AuthUser $authUser, Deal $deal): bool
    {
        if (! $this->belongsToOrganization($authUser, $deal)) {
            return false;
        }

        return $authUser->can('View:Deal');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Deal');
    }

    public function update(AuthUser $authUser, Deal $deal): bool
    {
        if (! $this->belongsToOrganization($authUser, $deal)) {
            return false;
        }

        return $authUser->can('Update:Deal');
    }

    public function delete(AuthUser $authUser, Deal $deal): bool
    {
        if (! $this->belongsToOrganization($authUser, $deal)) {
            return false;
        }

        return $authUser->can('Delete:Deal');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Deal');
    }

    public function restore(AuthUser $authUser, Deal $deal): bool
    {
        if (! $this->belongsToOrganization($authUser, $deal)) {
            return false;
        }

        return $authUser->can('Restore:Deal');
    }

    public function forceDelete(AuthUser $authUser, Deal $deal): bool
    {
        if (! $this->belongsToOrganization($authUser, $deal)) {
            return false;
        }

        return $authUser->can('ForceDelete:Deal');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Deal');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Deal');
    }

    public function replicate(AuthUser $authUser, Deal $deal): bool
    {
        if (! $this->belongsToOrganization($authUser, $deal)) {
            return false;
        }

        return $authUser->can('Replicate:Deal');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Deal');
    }

    private function belongsToOrganization(AuthUser $authUser, Deal $deal): bool
    {
        if ($deal->crmcore_customer_id !== null) {
            return (int) ($deal->crmcoreCustomer?->organization_id ?? 0) === (int) $authUser->organization_id;
        }

        return (int) ($deal->company_id ?? 0) === (int) ($authUser->company_id ?? $authUser->organization_id ?? 0);
    }

}
