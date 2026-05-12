<?php

namespace Modules\Asset\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Asset\Console\ActivateModuleCommand;

class AssetServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->registerCommands();
    }

    public function register(): void
    {
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('asset.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'asset'
        );

        $this->mergeConfigFrom(
            module_path('asset', 'Config/xss_ignore.php'),
            'asset::xss_ignore'
        );
    }

    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/asset');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath,
        ], 'views');

        $this->loadViewsFrom([$sourcePath], 'asset');
    }

    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/asset');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'asset');

        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'asset');
        }
    }

    private function registerCommands(): void
    {
        $this->commands(
            [
                ActivateModuleCommand::class,
            ]
        );
    }
}
