<?php

namespace Modules\TitanProAdmin\Actions;

use Modules\TitanProAdmin\Events\ModuleEnabled;
use Modules\TitanProAdmin\Models\AdminAuditLog;
use Modules\TitanProAdmin\Services\ModuleToggleService;

class EnableModuleAction
{
    public function __construct(private readonly ModuleToggleService $service) {}

    public function execute(int $tenantId, string $moduleName, int|string|null $actorId = null): array
    {
        $enabled = $this->service->enableModule($tenantId, $moduleName);

        AdminAuditLog::query()->create([
            'actor_id' => $actorId,
            'target_company_id' => $tenantId,
            'action' => 'module.enable',
            'context' => ['module' => $moduleName],
        ]);

        event(new ModuleEnabled($tenantId, $moduleName, $actorId));

        return $enabled;
    }
}
