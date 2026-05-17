<?php

namespace Modules\TitanProAdmin\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\TitanProAdmin\Filament\Pages\PlatformHealthPage;
use Modules\TitanProAdmin\Filament\Pages\ModuleManagerPage;
use Modules\TitanProAdmin\Filament\Pages\AuditLogPage;
use Modules\TitanProAdmin\Filament\Pages\TenantConfigPage;

/**
 * TitanPro admin panel native plugin.
 *
 * Adds platform-level pages that have no home in any domain module:
 *  - Platform Health dashboard (AI providers, queue, storage)
 *  - Module Manager (enable/disable modules, view manifests)
 *  - Global Audit Log viewer
 *  - Tenant Configuration (branding, features, limits)
 */
class TitanProAdminPlugin implements Plugin
{
    public function getId(): string
    {
        return 'titan-pro-admin';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            PlatformHealthPage::class,
            ModuleManagerPage::class,
            AuditLogPage::class,
            TenantConfigPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
