<?php

namespace Modules\TitanCommand\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TitanCommandServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'TitanCommand';

    public function register(): void
    {
        $this->registerConfig();
    }

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerViews();

        $migrationsPath = module_path($this->moduleName, 'Database/Migrations');
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }

        $webRoutes = module_path($this->moduleName, 'Routes/web.php');
        if (file_exists($webRoutes)) {
            Route::middleware('web')->group($webRoutes);
        }

        $apiRoutes = module_path($this->moduleName, 'Routes/api.php');
        if (file_exists($apiRoutes)) {
            Route::middleware('api')->prefix('api')->group($apiRoutes);
        }
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'titancommand');
    }

    protected function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.strtolower($this->moduleName));

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'titancommand');
        } else {
            $moduleLangPath = module_path($this->moduleName, 'Resources/lang');
            if (is_dir($moduleLangPath)) {
                $this->loadTranslationsFrom($moduleLangPath, 'titancommand');
            }
        }
    }

    protected function registerViews(): void
    {
        $viewsPath = module_path($this->moduleName, 'Resources/views');
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'titancommand');
        }
    }
}
