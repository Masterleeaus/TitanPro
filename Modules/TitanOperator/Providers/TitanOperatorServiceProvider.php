<?php

namespace Modules\TitanOperator\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TitanOperator\Services\KnowledgeBaseEmbeddingPipeline;

class TitanOperatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(KnowledgeBaseEmbeddingPipeline::class);
    }

    public function boot(): void
    {
        $routePath = __DIR__ . '/../Routes/api.php';
        if (is_file($routePath)) {
            $this->loadRoutesFrom($routePath);
        }

        $migrationsPath = __DIR__ . '/../Database/Migrations';
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }

        $viewsPath = __DIR__ . '/../Resources/views';
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'titan-operator');
        }
    }
}
