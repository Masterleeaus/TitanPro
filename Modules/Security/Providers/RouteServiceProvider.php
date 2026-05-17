<?php

namespace Modules\Security\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = 'Modules\Security\Http\Controllers';

    public function boot(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapSettingRoutes();
        $this->mapOptionalWebRoutes();
        $this->mapInternalRoutes();
        $this->mapChannelRoutes();
        parent::boot();
    }

    protected function mapWebRoutes(): void
    {
        $path = module_path('Security', 'Routes/web.php');
        if (is_file($path)) {
            Route::middleware('web')->group($path);
        }
    }

    protected function mapSettingRoutes(): void
    {
        $path = module_path('Security', 'Routes/web-settings.php');
        if (is_file($path)) {
            Route::middleware('web')->group($path);
        }
    }

    protected function mapOptionalWebRoutes(): void
    {
        foreach (['admin.php', 'workflow.php', 'ai.php', 'webhook.php'] as $routeFile) {
            $path = module_path('Security', 'Routes/'.$routeFile);
            if (is_file($path)) {
                Route::middleware('web')->group($path);
            }
        }
    }

    protected function mapInternalRoutes(): void
    {
        $path = module_path('Security', 'Routes/internal.php');
        if (is_file($path)) {
            Route::prefix('internal/security')
                ->middleware(['web', 'auth'])
                ->group($path);
        }
    }

    protected function mapChannelRoutes(): void
    {
        $path = module_path('Security', 'Routes/channels.php');
        if (is_file($path)) {
            require $path;
        }
    }

    protected function mapApiRoutes(): void
    {
        $path = module_path('Security', 'Routes/api.php');
        if (is_file($path)) {
            Route::prefix('api')->middleware('api')->group($path);
        }
    }
}
