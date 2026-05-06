<?php

namespace Modules\TitanCore\Providers;

use App\Http\Middleware\SuperAdmin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\TitanCore\Console\Commands\ModulesDepsCommand;
use Modules\TitanCore\Console\Commands\ModulesDoctorCommand;
use Modules\TitanCore\Console\Commands\ModulesEnableCommand;
use Modules\TitanCore\Console\Commands\ModulesHealthCommand;
use Modules\TitanCore\Console\Commands\ModulesUpgradeCommand;
use Modules\TitanCore\Console\Commands\SyncTitanDocsKnowledgeCommand;
use Modules\TitanCore\Console\SyncTitanAgentsCommand;
use Modules\TitanCore\Services\Providers\TitanAiProvider;
use Modules\TitanCore\Services\TitanAiClient;
use Modules\TitanCore\Services\TitanCoreRouter;
use Modules\TitanCore\Support\ModuleDependencyGraph;

class TitanCoreServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->validateTitanConfig();

        $this->registerTranslations();
        $this->registerViews();

        // Migrations
        $migrationsPath = module_path('TitanCore').'/Database/Migrations';
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }

        // Super Admin lock middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('titancore.superadmin', SuperAdmin::class);

        // Web routes
        $web = __DIR__.'/../Routes/web.php';
        if (file_exists($web)) {
            Route::middleware('web')->group($web);
        }

        // API routes (mounted under /api)
        $api = __DIR__.'/../Routes/api.php';
        if (file_exists($api)) {
            Route::middleware('api')->prefix('api')->group($api);
        }

        // Console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncTitanDocsKnowledgeCommand::class,
                SyncTitanAgentsCommand::class,
                ModulesUpgradeCommand::class,
                ModulesHealthCommand::class,
                ModulesDoctorCommand::class,
                ModulesDepsCommand::class,
                ModulesEnableCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        // App-level Titan config files (config/ directory)
        $this->mergeConfigFrom(config_path('titan-modules.php'), 'titan-modules');
        $this->mergeConfigFrom(config_path('titan-ai.php'), 'titan-ai');
        $this->mergeConfigFrom(config_path('titan-model-runtime.php'), 'titan-model-runtime');

        // Module-level config overrides
        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'titancore');
        $this->mergeConfigFrom(__DIR__.'/../Config/titan_agents.php', 'titan_agents');

        // Bind Titan AI client/provider/router (lazy + safe)
        $this->app->singleton(TitanAiClient::class, function () {
            $cfg = config('titancore.providers.titanai', []);

            return new TitanAiClient(
                (string) ($cfg['base_url'] ?? ''),
                (string) ($cfg['api_key'] ?? ''),
                (int) ($cfg['timeout_seconds'] ?? 60),
            );
        });

        $this->app->singleton(TitanAiProvider::class, function ($app) {
            return new TitanAiProvider(
                $app->make(TitanAiClient::class)
            );
        });

        $this->app->singleton(TitanCoreRouter::class, function ($app) {
            return new TitanCoreRouter(
                $app->make(TitanAiProvider::class)
            );
        });

        // no bindings; keep lightweight
        $this->app->singleton(
            ModuleDependencyGraph::class,
            fn ($app) => new ModuleDependencyGraph(
                $app['modules']
            )
        );
    }

    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/titancore');
        $sourcePath = module_path('TitanCore').'/Resources/views';

        if (is_dir($sourcePath)) {
            $this->publishes([
                $sourcePath => $viewPath,
            ], 'views');

            $this->loadViewsFrom($this->getPublishableViewPaths($sourcePath), 'titancore');
        }
    }

    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/titancore');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'titancore');
        } else {
            $moduleLang = module_path('TitanCore').'/Resources/lang';
            if (is_dir($moduleLang)) {
                $this->loadTranslationsFrom($moduleLang, 'titancore');
            }
        }
    }

    private function getPublishableViewPaths(string $sourcePath): array
    {
        $paths = [];

        foreach (config('view.paths') as $path) {
            $candidate = $path.'/modules/titancore';

            if (is_dir($candidate)) {
                $paths[] = $candidate;
            }
        }

        $paths[] = $sourcePath;

        return $paths;
    }

    /**
     * Validate that all required Titan config keys are present.
     * Throws a RuntimeException immediately so the application fails loudly
     * rather than producing silent "Undefined array key" errors at runtime.
     */
    private function validateTitanConfig(): void
    {
        $missing = [];

        // titan-modules: path must be a non-empty string
        if (empty(config('titan-modules.path'))) {
            $missing[] = 'titan-modules.path';
        }

        // titan-ai: default_provider must be a non-empty string
        if (empty(config('titan-ai.default_provider'))) {
            $missing[] = 'titan-ai.default_provider';
        }

        // titan-model-runtime: providers must be a non-empty array
        $providers = config('titan-model-runtime.providers');
        if (empty($providers) || ! is_array($providers)) {
            $missing[] = 'titan-model-runtime.providers';
        }

        if (! empty($missing)) {
            throw new \RuntimeException(
                'Titan configuration is incomplete. Missing required keys: '.implode(', ', $missing).'. '
                .'Check your .env file and ensure config/titan-modules.php, config/titan-ai.php, '
                .'and config/titan-model-runtime.php are present.'
            );
        }
    }
}
