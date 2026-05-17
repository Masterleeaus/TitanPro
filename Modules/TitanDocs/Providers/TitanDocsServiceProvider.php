<?php

namespace Modules\TitanDocs\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class TitanDocsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $configPath = __DIR__ . '/../Config/config.php';

        if (is_file($configPath)) {
            $this->mergeConfigFrom($configPath, 'titandocs');
        }
    }

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerViews();
        $this->registerRoutes();
        $this->registerViewFallbacks();
        $this->registerMigrations();
    }

    protected function registerViews(): void
    {
        $viewPath = is_dir(__DIR__ . '/../Resources/views')
            ? __DIR__ . '/../Resources/views'
            : __DIR__ . '/../resources/views';

        $this->loadViewsFrom($viewPath, 'aidocument');
        $this->loadViewsFrom($viewPath, 'titandocs');
    }

    protected function registerRoutes(): void
    {
        foreach ([
            __DIR__ . '/../Routes/web.php',
        ] as $routePath) {
            if (is_file($routePath)) {
                $this->loadRoutesFrom($routePath);
            }
        }
    }

    protected function registerTranslations(): void
    {
        $langPath = is_dir(__DIR__ . '/../Resources/lang')
            ? __DIR__ . '/../Resources/lang'
            : __DIR__ . '/../resources/lang';

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'titandocs');
            $this->loadJsonTranslationsFrom($langPath);
        }
    }

    protected function registerMigrations(): void
    {
        foreach ([
            __DIR__ . '/../Database/Migrations',
            __DIR__ . '/../Database/migrations',
        ] as $migrationPath) {
            if (is_dir($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }
        }
    }

    /**
     * Some installs expect certain globals (e.g. $gdpr) to exist when rendering the main menu.
     * We provide a safe fallback so Titan Docs pages never crash the whole sidebar/menu.
     */
    protected function registerViewFallbacks(): void
    {
        View::composer('sections.menu', function ($view) {
            $data = $view->getData();

            if (!array_key_exists('gdpr', $data) || $data['gdpr'] === null) {
                $view->with('gdpr', (object) ['enable_gdpr' => 0]);
            }
        });
    }
}
