<?php

declare(strict_types=1);

namespace Modules\Dispatch\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');
        Route::middleware('api')->prefix('api/dispatch')->group(__DIR__.'/../Routes/api.php');
    }
}
