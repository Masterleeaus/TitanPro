<?php

namespace App\Extensions\TitanPulse\Providers;

use Illuminate\Support\ServiceProvider;

class TitanPulseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind services here if needed later.
    }

    public function boot(): void
    {
        // Load migrations (dev installs). Production may still prefer SQL-first installs.
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Extensions\TitanPulse\Console\Commands\TitanPulseRunCommand::class,
            ]);
        }
    }
}
