<?php

namespace Modules\TitanProAdmin\Filament\Pages;

use Filament\Pages\Page;
use Modules\TitanProAdmin\Actions\SuspendTenantAction;
use Modules\TitanProAdmin\Actions\UpdateSystemConfigAction;
use Modules\TitanProAdmin\Models\TenantConfig;
use Modules\TitanProAdmin\Policies\SuperAdminPolicy;
use Modules\TitanProAdmin\Services\TenantService;

class TenantConfigPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Tenant Config';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 40;
    protected static string $view = 'titanproadmin::pages.tenant-config';

    public function getTitle(): string
    {
        return 'Tenant Configuration';
    }

    public static function canAccess(): bool
    {
        $user = auth('super_admin')->user() ?? auth()->user();

        return app(SuperAdminPolicy::class)->access($user);
    }

    public function tenantConfig(int $tenantId): TenantConfig
    {
        return app(TenantService::class)->getTenantConfig($tenantId);
    }

    public function updateTenantConfig(int $tenantId, array $config): TenantConfig
    {
        return app(UpdateSystemConfigAction::class)->execute(
            tenantId: $tenantId,
            config: $config,
            actorId: auth('super_admin')->id() ?? auth()->id()
        );
    }

    public function suspendTenant(int $tenantId): void
    {
        app(SuspendTenantAction::class)->execute(
            tenantId: $tenantId,
            actorId: auth('super_admin')->id() ?? auth()->id()
        );
    }
}
