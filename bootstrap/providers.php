<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
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
    // Only registered when Telescope is installed (dev environments only)
    ...(class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)
        ? [App\Providers\TelescopeServiceProvider::class]
        : []),
];
