<?php

namespace Modules\CRMCore\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Modules\CRMCore\Filament\CRMCorePlugin;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register the CRMCore Filament plugin with the declared panel.
        $panelId = config('crmcore.filament_panel', 'titanpro');

        if (! class_exists(Panel::class)) {
            return;
        }

        $this->callAfterResolving('filament', function () use ($panelId): void {
            if (! \Filament\Facades\Filament::hasPanelWithId($panelId)) {
                return;
            }

            $panel = \Filament\Facades\Filament::getPanel($panelId);
            $panel->plugin(CRMCorePlugin::make());
        });
    }
}

