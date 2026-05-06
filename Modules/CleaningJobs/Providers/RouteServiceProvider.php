<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');
        if (file_exists(__DIR__.'/../Routes/api.php')) { Route::middleware('api')->prefix('api')->group(__DIR__.'/../Routes/api.php'); }
        if (file_exists(__DIR__.'/../Routes/internal.php')) { Route::middleware(['web','auth'])->group(__DIR__.'/../Routes/internal.php'); }
        if (file_exists(__DIR__.'/../Routes/tenant.php')) { Route::middleware(['web','auth'])->group(__DIR__.'/../Routes/tenant.php'); }
    }
}
