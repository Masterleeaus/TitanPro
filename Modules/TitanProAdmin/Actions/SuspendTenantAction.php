<?php

namespace Modules\TitanProAdmin\Actions;

use App\Models\Organization;
use Modules\TitanProAdmin\Events\TenantSuspended;
use Modules\TitanProAdmin\Models\AdminAuditLog;
use Modules\TitanProAdmin\Services\TenantService;

class SuspendTenantAction
{
    public function __construct(private readonly TenantService $service) {}

    public function execute(int $tenantId, int|string|null $actorId = null): Organization
    {
        $tenant = $this->service->suspendTenant($tenantId);

        AdminAuditLog::query()->create([
            'actor_id' => $actorId,
            'target_company_id' => $tenantId,
            'action' => 'tenant.suspend',
            'context' => null,
        ]);

        event(new TenantSuspended($tenantId, $actorId));

        return $tenant;
    }
}
