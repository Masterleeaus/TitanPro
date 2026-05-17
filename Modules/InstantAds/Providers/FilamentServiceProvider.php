<?php

namespace Modules\InstantAds\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Modules\InstantAds\Filament\Plugin\InstantAdsPlugin;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('instantads.filament.plugin', fn () => InstantAdsPlugin::make());
    }

    public function boot(): void
    {
        $panelId = config('instantads.filament_panel', 'titanstudio');

        if (! class_exists(Panel::class)) {
            return;
        }

        $this->callAfterResolving('filament', function () use ($panelId): void {
            if (! \Filament\Facades\Filament::hasPanelWithId($panelId)) {
                return;
            }

            $panel = \Filament\Facades\Filament::getPanel($panelId);
            $panel->plugin(InstantAdsPlugin::make());
        });
    }
}
