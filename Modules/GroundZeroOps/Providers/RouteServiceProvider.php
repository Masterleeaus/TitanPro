<?php

namespace Modules\GroundZeroOps\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function map(): void
    {
        $webPath = __DIR__ . '/../Routes/web.php';
        $apiPath = __DIR__ . '/../Routes/api.php';
        $channelsPath = __DIR__ . '/../Routes/channels.php';

        if (file_exists($webPath)) {
            Route::middleware('web')->group($webPath);
        }

        if (file_exists($apiPath)) {
            Route::prefix('api')->group($apiPath);
        }

        if (file_exists($channelsPath)) {
            Broadcast::routes();
            require $channelsPath;
        }
    }
}
