<?php

namespace Modules\TitanProAdmin\Actions;

use Modules\TitanProAdmin\Models\AdminAuditLog;
use Modules\TitanProAdmin\Models\TenantConfig;
use Modules\TitanProAdmin\Services\TenantService;

class UpdateSystemConfigAction
{
    public function __construct(private readonly TenantService $service) {}

    public function execute(int $tenantId, array $config, int|string|null $actorId = null): TenantConfig
    {
        $record = $this->service->updateTenantConfig($tenantId, $config, $actorId);

        AdminAuditLog::query()->create([
            'actor_id' => $actorId,
            'target_company_id' => $tenantId,
            'action' => 'tenant.config.update',
            'context' => ['keys' => array_keys($config)],
        ]);

        return $record;
    }
}
