<?php

namespace Modules\Security\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Security\Contracts\Services\SecurityModuleServiceInterface;
use Modules\Security\Services\Core\SecurityModuleService;
use Modules\Security\Contracts\Repositories\SecurityRepositoryInterface;
use Modules\Security\Repositories\Eloquent\SecurityRepository;
use Modules\Security\Console\Diagnostics\SecurityModuleHealthCommand;
use Modules\Security\Console\Repair\SecurityModuleRepairCommand;
use Modules\Security\Console\Diagnostics\SecurityStructureAuditCommand;
use Modules\Security\Security\Audit\SecurityAuditLogger;
use Modules\Security\Support\Diagnostics\SecurityModuleDiagnostic;
use Modules\Security\Support\Diagnostics\SecurityStructureAudit;
use Modules\Security\Support\Health\SecurityHealthCheck;
use Modules\Security\Http\Middleware\EnsureSecurityFeatureEnabled;
use Modules\Security\Security\Sanitization\InputSanitizer;
use Modules\Security\Security\Secrets\SensitiveValueRedactor;
use Modules\Security\Security\RateLimiting\SecurityRateLimitRegistrar;
use Modules\Security\Support\Validators\SecureFileUploadValidator;
use Modules\Security\Console\Installers\SecurityInstallVerifyCommand;
use Modules\Security\Console\Repair\SecurityCacheWarmCommand;
use Modules\Security\Console\Diagnostics\SecurityOperationalReadinessCommand;
use Modules\Security\Services\Core\SecurityOperationalReadinessService;
use Modules\Security\Contracts\Services\OperationalReadinessServiceInterface;
use Modules\Security\Contracts\Services\CleanerOperationsServiceInterface;
use Modules\Security\Contracts\Services\CleanerReportingServiceInterface;
use Modules\Security\Services\Domain\CleanerOperationsService;
use Modules\Security\Services\Reporting\CleanerReportingService;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Security';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'security';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        if (class_exists(SecurityRateLimitRegistrar::class)) {
            $this->app->make(SecurityRateLimitRegistrar::class)->register();
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                SecurityModuleHealthCommand::class,
                SecurityModuleRepairCommand::class,
                SecurityStructureAuditCommand::class,
                SecurityOperationalReadinessCommand::class,
                SecurityCacheWarmCommand::class,
                SecurityInstallVerifyCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(BroadcastServiceProvider::class);
        $this->app->register(WorkflowServiceProvider::class);
        $this->app->register(AIServiceProvider::class);
        $this->app->register(FilamentServiceProvider::class);

        $this->app->singleton(SecurityRepositoryInterface::class, SecurityRepository::class);
        $this->app->singleton(SecurityModuleServiceInterface::class, SecurityModuleService::class);
        $this->app->singleton(OperationalReadinessServiceInterface::class, SecurityOperationalReadinessService::class);
        $this->app->singleton(CleanerOperationsServiceInterface::class, CleanerOperationsService::class);
        $this->app->singleton(CleanerReportingServiceInterface::class, CleanerReportingService::class);
        $this->app->singleton(SecurityModuleDiagnostic::class);
        $this->app->singleton(SecurityStructureAudit::class);
        $this->app->singleton(SecurityHealthCheck::class);
        $this->app->singleton(SecurityAuditLogger::class);
        $this->app->singleton(InputSanitizer::class);
        $this->app->singleton(SensitiveValueRedactor::class);
        $this->app->singleton(SecureFileUploadValidator::class);
        $this->app->singleton(SecurityRateLimitRegistrar::class);

        $this->registerBlueprintConfig();

        $this->registerRouteMiddleware();
    }


    /**
     * Register module middleware aliases when the router is available.
     */
    protected function registerRouteMiddleware(): void
    {
        if (! $this->app->bound('router')) {
            return;
        }

        $router = $this->app['router'];
        if (method_exists($router, 'aliasMiddleware')) {
            $router->aliasMiddleware('security.feature', EnsureSecurityFeatureEnabled::class);
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $publishable = [];
        foreach (glob(module_path($this->moduleName, 'Config/*.php')) ?: [] as $configFile) {
            $name = basename($configFile);
            $publishName = $name === 'config.php' ? $this->moduleNameLower . '.php' : $this->moduleNameLower . '_' . $name;
            $publishable[$configFile] = config_path($publishName);
        }

        if ($publishable !== []) {
            $this->publishes($publishable, 'config');
        }
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register blueprint config files under explicit names so merged legacy
     * features can be discovered without colliding with the root config.
     */
    protected function registerBlueprintConfig(): void
    {
        foreach ([
            'permissions',
            'features',
            'workflows',
            'ai',
            'billing',
            'notifications',
            'integrations',
            'registry',
            'module',
            'cleaners',
        ] as $config) {
            $path = module_path($this->moduleName, 'Config/' . $config . '.php');
            if (file_exists($path)) {
                $this->mergeConfigFrom($path, $this->moduleNameLower . '_' . $config);
            }
        }
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        // Compatibility aliases for merged legacy modules.
        $legacyViews = [
            'trinoutpermit' => module_path($this->moduleName, 'Resources/views/_legacy/trinoutpermit'),
            'workpermits' => module_path($this->moduleName, 'Resources/views/_legacy/workpermits'),
            'traccesscard' => module_path($this->moduleName, 'Resources/views/_legacy/traccesscard'),
        ];

        foreach ($legacyViews as $alias => $path) {
            if (is_dir($path)) {
                $this->loadViewsFrom($path, $alias);
            }
        }

    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'));
        }

        // Compatibility translation namespaces for merged legacy modules.
        $legacyLang = [
            'trinoutpermit' => module_path($this->moduleName, 'Resources/lang/_legacy/trinoutpermit'),
            'trworkpermits' => module_path($this->moduleName, 'Resources/lang/_legacy/trworkpermits'),
            'traccesscard' => module_path($this->moduleName, 'Resources/lang/_legacy/traccesscard'),
        ];

        foreach ($legacyLang as $alias => $path) {
            if (is_dir($path)) {
                $this->loadTranslationsFrom($path, $alias);
                $this->loadJsonTranslationsFrom($path);
            }
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
