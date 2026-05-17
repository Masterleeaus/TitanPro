<?php

namespace Modules\TitanProAdmin\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TitanProAdmin\Policies\SuperAdminPolicy;
use Modules\TitanProAdmin\Services\LicenseService;
use Modules\TitanProAdmin\Services\ModuleToggleService;
use Modules\TitanProAdmin\Services\PlatformHealthService;
use Modules\TitanProAdmin\Services\TenantService;

class TitanProAdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'titanproadmin');
        $this->mergeConfigFrom(__DIR__.'/../Config/features.php', 'titanproadmin.features');
        $this->mergeConfigFrom(__DIR__.'/../Config/permissions.php', 'titanproadmin.permissions');

        $this->registerSuperAdminGuard();

        $this->app->singleton(TenantService::class);
        $this->app->singleton(ModuleToggleService::class);
        $this->app->singleton(LicenseService::class);
        $this->app->singleton(PlatformHealthService::class);

        $this->app->singleton(SuperAdminPolicy::class);

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(FilamentServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'titanproadmin');
    }

    private function registerSuperAdminGuard(): void
    {
        $guards = config('auth.guards', []);

        if (! isset($guards['super_admin'])) {
            $guards['super_admin'] = config('titanproadmin.guards.super_admin', [
                'driver' => 'session',
                'provider' => 'users',
            ]);

            config(['auth.guards' => $guards]);
        }
    }
}
