<?php

namespace Modules\TitanProAdmin\Filament\Pages;

use Filament\Pages\Page;
use Modules\TitanProAdmin\Actions\DisableModuleAction;
use Modules\TitanProAdmin\Actions\EnableModuleAction;
use Modules\TitanProAdmin\Policies\SuperAdminPolicy;

class ModuleManagerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationLabel = 'Module Manager';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 20;
    protected static string $view = 'titanproadmin::pages.module-manager';

    public function getTitle(): string
    {
        return 'Module Manager';
    }

    public static function canAccess(): bool
    {
        $user = auth('super_admin')->user() ?? auth()->user();

        return app(SuperAdminPolicy::class)->access($user);
    }

    public function enableModuleForTenant(int $tenantId, string $moduleName): array
    {
        return app(EnableModuleAction::class)->execute(
            tenantId: $tenantId,
            moduleName: $moduleName,
            actorId: auth('super_admin')->id() ?? auth()->id()
        );
    }

    public function disableModuleForTenant(int $tenantId, string $moduleName): array
    {
        return app(DisableModuleAction::class)->execute(
            tenantId: $tenantId,
            moduleName: $moduleName,
            actorId: auth('super_admin')->id() ?? auth()->id()
        );
    }
}
