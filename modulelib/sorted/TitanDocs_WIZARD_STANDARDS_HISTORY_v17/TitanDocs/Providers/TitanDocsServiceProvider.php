<?php

namespace Modules\TitanDocs\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class TitanDocsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerViews();
        $this->registerRoutes();
        $this->registerViewFallbacks();
    }

    protected function registerViews(): void
    {
        $viewPath = is_dir(__DIR__ . '/../Resources/views')
            ? __DIR__ . '/../Resources/views'
            : __DIR__ . '/../resources/views';

        $this->loadViewsFrom($viewPath, 'titandocs');
    }

    protected function registerRoutes(): void
    {
        $routePath = file_exists(__DIR__ . '/../Routes/web.php')
            ? __DIR__ . '/../Routes/web.php'
            : __DIR__ . '/../routes/web.php';

        if (file_exists($routePath)) {
            $this->loadRoutesFrom($routePath);
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
