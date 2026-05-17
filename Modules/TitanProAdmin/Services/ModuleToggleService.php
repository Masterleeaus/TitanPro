<?php

namespace Modules\TitanProAdmin\Services;

use Modules\TitanProAdmin\Models\ModuleToggle;

class ModuleToggleService
{
    public function enableModule(int $tenantId, string $moduleName): array
    {
        $tenant = ModuleToggle::withoutGlobalScopes()->findOrFail($tenantId);
        $enabled = collect($tenant->enabled_modules ?? [])->push($moduleName)->unique()->values()->all();

        $tenant->forceFill(['enabled_modules' => $enabled])->save();

        return $enabled;
    }

    public function disableModule(int $tenantId, string $moduleName): array
    {
        $tenant = ModuleToggle::withoutGlobalScopes()->findOrFail($tenantId);
        $enabled = collect($tenant->enabled_modules ?? [])->reject(
            static fn (string $module): bool => $module === $moduleName
        )->values()->all();

        $tenant->forceFill(['enabled_modules' => $enabled])->save();

        return $enabled;
    }
}
