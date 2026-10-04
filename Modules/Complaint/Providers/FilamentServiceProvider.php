<?php

namespace Modules\Complaint\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Modules\Complaint\Filament\Plugin\ComplaintPlugin;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('complaint.filament.plugin', fn () => ComplaintPlugin::make());
    }

    public function boot(): void
    {
        $panelId = config('complaint.filament_panel', 'zerofuss');

        if (! class_exists(Panel::class)) {
            return;
        }

        $this->callAfterResolving('filament', function () use ($panelId): void {
            $panel = \Filament\Facades\Filament::getPanel($panelId, isStrict: false);

            if ($panel === null) {
                return;
            }
            $panel->plugin(ComplaintPlugin::make());
        });
    }
}
