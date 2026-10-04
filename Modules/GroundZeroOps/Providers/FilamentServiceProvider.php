<?php

namespace Modules\GroundZeroOps\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Modules\GroundZeroOps\Filament\Plugin\GroundZeroOpsPlugin;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $panelId = config('groundzeroops.filament_panel', 'groundzero');

        if (! class_exists(Panel::class)) {
            return;
        }

        $this->callAfterResolving('filament', function () use ($panelId): void {
            $panel = \Filament\Facades\Filament::getPanel($panelId, isStrict: false);

            if ($panel === null) {
                return;
            }

            $panel->plugin(GroundZeroOpsPlugin::make());
        });
    }
}
