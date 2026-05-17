<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class TitanLivewireFallbackServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component(
            'alizharb.filament-themes-manager.widgets.themes-overview',
            \App\Livewire\Alizharb\FilamentThemesManager\Widgets\ThemesOverview::class
        );
    }
}
