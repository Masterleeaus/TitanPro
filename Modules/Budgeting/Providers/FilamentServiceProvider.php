<?php

declare(strict_types=1);

namespace Modules\Budgeting\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Modules\Budgeting\Filament\BudgetingPlugin;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (! class_exists(Panel::class)) {
            return;
        }

        $this->callAfterResolving('filament', function (): void {
            if (! \Filament\Facades\Filament::hasPanelWithId('budgeting')) {
                return;
            }

            $panel = \Filament\Facades\Filament::getPanel('budgeting');
            $panel->plugin(BudgetingPlugin::make());
        });
    }
}
