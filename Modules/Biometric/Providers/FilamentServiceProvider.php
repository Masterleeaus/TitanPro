<?php

namespace Modules\Biometric\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Biometric\Filament\Plugin\BiometricPlugin;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('biometric.filament.plugin', fn () => BiometricPlugin::make());
    }

    public function boot(): void
    {
        $this->loadViewsFrom(module_path('Biometric', 'Resources/views'), 'biometric');
    }
}

