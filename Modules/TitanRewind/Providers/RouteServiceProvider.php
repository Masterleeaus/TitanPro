<?php

namespace Modules\TitanRewind\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['web', 'auth'])
            ->prefix('titan-rewind')
            ->name('titan-rewind.')
            ->group(module_path('TitanRewind', 'Routes/web.php'));

        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/titan-rewind')
            ->name('titan-rewind.api.')
            ->group(module_path('TitanRewind', 'Routes/api.php'));
    }
}
