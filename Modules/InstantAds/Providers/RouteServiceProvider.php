<?php

namespace Modules\InstantAds\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $moduleNamespace = 'Modules\InstantAds\Http\Controllers';

    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapApiRoutes(): void
    {
        $apiPath = __DIR__ . '/../Routes/api.php';

        if (! file_exists($apiPath)) {
            return;
        }

        Route::middleware('api')
            ->prefix('api/v1/instant-ads')
            ->name('instant-ads.api.')
            ->namespace($this->moduleNamespace)
            ->group($apiPath);
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(__DIR__ . '/../Routes/web.php');
    }
}
