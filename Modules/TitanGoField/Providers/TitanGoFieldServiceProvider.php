<?php

namespace Modules\TitanGoField\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TitanGoField\Services\RecurrenceService;
use Modules\TitanGoField\Services\FieldJobTotalsService;

class TitanGoFieldServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'titango_field');
        $this->mergeConfigFrom(__DIR__.'/../Config/features.php', 'titango_field.features');
        $this->mergeConfigFrom(__DIR__.'/../Config/ai.php', 'titango_field.ai');

        $this->app->singleton(RecurrenceService::class);
        $this->app->singleton(FieldJobTotalsService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'titango_field');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'titango_field');

        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('titango_field.php'),
        ], 'titango_field-config');
    }
}
