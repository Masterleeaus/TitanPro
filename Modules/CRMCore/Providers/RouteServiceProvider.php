<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function map(): void
    {
        foreach (['web', 'api', 'internal', 'tenant'] as $surface) {
            $path = __DIR__ . '/../Routes/' . $surface . '.php';

            if (! file_exists($path)) {
                continue;
            }

            $route = Route::middleware($surface === 'api' ? ['api'] : ['web', 'auth']);

            if ($surface !== 'web') {
                $route->prefix('crmcore/' . $surface)->name('crmcore.' . $surface . '.');
            }

            $route->group($path);
        }
    }
}
