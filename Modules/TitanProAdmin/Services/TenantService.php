<?php

namespace Modules\TitanProAdmin\Services;

use App\Models\Organization;
use App\Models\User;
use Modules\TitanProAdmin\Models\TenantConfig;

class TenantService
{
    public function getTenantConfig(int $tenantId): TenantConfig
    {
        return TenantConfig::query()->firstOrCreate(
            ['target_company_id' => $tenantId],
            ['config' => []]
        );
    }

    public function updateTenantConfig(int $tenantId, array $config, int|string|null $actorId = null): TenantConfig
    {
        $record = $this->getTenantConfig($tenantId);
        $record->fill([
            'config' => $config,
            'updated_by' => $actorId,
        ]);
        $record->save();

        return $record;
    }

    public function suspendTenant(int $tenantId): Organization
    {
        $this->assertCrossTenantAccess($tenantId);

        $tenant = Organization::withoutGlobalScopes()->findOrFail($tenantId);
        $tenant->forceFill(['suspended_at' => now()])->save();

        return $tenant;
    }

    public function assertCrossTenantAccess(int $tenantId): void
    {
        $query = User::withoutGlobalScopes()->where('organization_id', $tenantId);

        if (! $query->exists()) {
            throw new \RuntimeException("No cross-tenant identity context found for tenant [{$tenantId}].");
        }
    }
}
