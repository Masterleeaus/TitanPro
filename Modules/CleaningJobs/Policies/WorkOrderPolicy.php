<?php

namespace Modules\CleaningJobs\Policies;

use App\Models\User;
use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Tenancy\Resolvers\CompanyTenantResolver;

class WorkOrderPolicy
{
    public function __construct(
        private readonly CompanyTenantResolver $tenantResolver,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->tenantResolver->currentCompanyId($user) !== null;
    }

    public function view(User $user, WorkOrder $workOrder): bool
    {
        return $this->tenantResolver->belongsToCurrentTenant($workOrder, $user);
    }

    public function create(User $user): bool
    {
        return $this->tenantResolver->currentCompanyId($user) !== null;
    }

    public function update(User $user, WorkOrder $workOrder): bool
    {
        return $this->tenantResolver->belongsToCurrentTenant($workOrder, $user);
    }

    public function delete(User $user, WorkOrder $workOrder): bool
    {
        return $this->tenantResolver->belongsToCurrentTenant($workOrder, $user);
    }
}
