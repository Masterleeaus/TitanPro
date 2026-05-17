<?php

namespace Modules\ZeroPayModule\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'ZeroPayModule';

    protected string $moduleNameLower = 'zeropay-module';

    /**
     * Define route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapApiRoutes();
            $this->mapWebRoutes();
            $this->mapAdminRoutes();
        });
    }

    /**
     * Map API routes prefixed with /api/zeropay.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api/zeropay')
            ->name('api.zeropay.')
            ->group(module_path($this->moduleName, 'Routes/api.php'));
    }

    /**
     * Map web routes.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->name('zeropay.')
            ->group(module_path($this->moduleName, 'Routes/web.php'));
    }

    /**
     * Map admin routes.
     */
    protected function mapAdminRoutes(): void
    {
        Route::middleware(['web', 'auth:admin'])
            ->prefix('admin/zeropay')
            ->name('admin.zeropay.')
            ->group(module_path($this->moduleName, 'Routes/admin.php'));
    }

    /**
     * Configure the rate limiters for this module.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('zeropay-api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('zeropay-webhooks', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });
    }
}
