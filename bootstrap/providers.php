<?php

return [
    App\Providers\AppServiceProvider::class,
    // Titan platform providers — boot order matters:
    // 1. Module layer first (registry must exist before AI / security resolve it)
    App\Providers\TitanModuleServiceProvider::class,
    App\Providers\AutomationEngineServiceProvider::class,
    App\Extensions\TitanPulse\Providers\TitanPulseServiceProvider::class,
    App\Providers\TitanBlueprintServiceProvider::class,
    // 2. AI and security layers depend on the module registry being available
    App\Providers\TitanAiRuntimeServiceProvider::class,
    App\Providers\TitanModelRuntimeServiceProvider::class,
    App\Providers\TitanModuleSecurityServiceProvider::class,
    App\Providers\Filament\TitanProPanelProvider::class,
    App\Providers\Filament\GroundZeroPanelProvider::class,
    App\Providers\Filament\TitanQuotesPanelProvider::class,
    App\Providers\Filament\ZeroPayPanelProvider::class,
    App\Providers\Filament\TitanGoPanelProvider::class,
    App\Providers\Filament\ZeroFussPanelProvider::class,
    App\Providers\Filament\TitanSoloPanelProvider::class,
    App\Providers\Filament\TitanStudioPanelProvider::class,
    App\Providers\Filament\TitanNexusPanelProvider::class,
    App\Providers\FortifyServiceProvider::class,
    Modules\CRMCore\Providers\ModuleServiceProvider::class,
    Modules\TitanLeads\Providers\TitanLeadsServiceProvider::class,
    // Only registered when Telescope is installed (dev environments only)
    ...(class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)
        ? [App\Providers\TelescopeServiceProvider::class]
        : []),
];
