<?php

namespace Modules\Payroll\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $bindings = __DIR__.'/../Bootstrap/bindings.php';
        if (is_file($bindings)) {
            (require $bindings)($this->app);
        }

        $this->mergeConfigFrom(__DIR__.'/../Config/features.php', 'payroll.features');
        $this->mergeConfigFrom(__DIR__.'/../Config/workflows.php', 'payroll.workflows');
        $this->mergeConfigFrom(__DIR__.'/../Config/notifications.php', 'payroll.notifications');
        $this->mergeConfigFrom(__DIR__.'/../Config/cleaning.php', 'payroll.cleaning');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'payroll');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'payroll');
    }
}
