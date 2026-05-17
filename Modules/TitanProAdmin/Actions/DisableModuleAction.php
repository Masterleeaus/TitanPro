<?php

namespace Modules\TitanProAdmin\Actions;

use Modules\TitanProAdmin\Events\ModuleDisabled;
use Modules\TitanProAdmin\Models\AdminAuditLog;
use Modules\TitanProAdmin\Services\ModuleToggleService;

class DisableModuleAction
{
    public function __construct(private readonly ModuleToggleService $service) {}

    public function execute(int $tenantId, string $moduleName, int|string|null $actorId = null): array
    {
        $enabled = $this->service->disableModule($tenantId, $moduleName);

        AdminAuditLog::query()->create([
            'actor_id' => $actorId,
            'target_company_id' => $tenantId,
            'action' => 'module.disable',
            'context' => ['module' => $moduleName],
        ]);

        event(new ModuleDisabled($tenantId, $moduleName, $actorId));

        return $enabled;
    }
}
