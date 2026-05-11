<?php

namespace Modules\TitanCommand\Providers;

use Illuminate\Support\ServiceProvider;

class TitanCommandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'titancommand');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'titancommand');
    }
}
