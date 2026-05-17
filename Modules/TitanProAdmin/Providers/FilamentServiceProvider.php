<?php

namespace Modules\TitanProAdmin\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Modules\TitanProAdmin\Filament\Plugin\TitanProAdminPlugin;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $panelId = config('titanproadmin.filament_panel', 'titanpro');

        if (! class_exists(Panel::class)) {
            return;
        }

        $this->callAfterResolving('filament', function () use ($panelId): void {
            if (! \Filament\Facades\Filament::hasPanelWithId($panelId)) {
                return;
            }

            $panel = \Filament\Facades\Filament::getPanel($panelId);
            $panel->plugin(TitanProAdminPlugin::make());
        });
    }
}
