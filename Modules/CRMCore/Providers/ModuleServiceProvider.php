<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'crmcore');

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(PolicyServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(AutomationServiceProvider::class);
        $this->app->register(WorkflowServiceProvider::class);
        $this->app->register(TenancyServiceProvider::class);
        $this->app->register(BillingServiceProvider::class);
        $this->app->register(SearchServiceProvider::class);
        $this->app->register(FilamentServiceProvider::class);
        $this->app->register(ModuleBootServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'crmcore');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'crmcore');
    }
}
