<?php

namespace App\Extensions\ProductPhotography\Providers;

use Illuminate\Support\ServiceProvider;

class ProductPhotographyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $configPath = app_path('Extensions/ProductPhotography/config/productphotography.php');
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'productphotography');
        }
    }

    public function boot(): void
    {
        $routeFile = app_path('Extensions/ProductPhotography/routes/extensions/quotemaker.routes.php');
        if (file_exists($routeFile)) {
            $this->loadRoutesFrom($routeFile);
        }

        $viewPath = app_path('Extensions/ProductPhotography/resources/views');
        if (is_dir($viewPath)) {
            $this->loadViewsFrom($viewPath, 'productphotography');
        }

        $migrationPath = app_path('Extensions/ProductPhotography/database/migrations');
        if (is_dir($migrationPath)) {
            $this->loadMigrationsFrom($migrationPath);
        }
    }
}
