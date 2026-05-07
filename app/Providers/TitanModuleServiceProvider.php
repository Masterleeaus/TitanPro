<?php

namespace App\Providers;

use App\Platform\AI\AIManifestRegistry;
use App\Platform\AI\BlueprintAIManifestRegistry;
use App\Platform\Automation\AutomationRegistry;
use App\Platform\Filament\FilamentRegistry;
use App\Platform\Modules\BlueprintManifestLoader;
use App\Platform\Modules\ChannelManifestRegistry;
use App\Platform\Modules\DashboardRegistry;
use App\Platform\Modules\ManifestLoader;
use App\Platform\Modules\ModuleKernel;
use App\Platform\Modules\ModuleManifestRegistryLoader;
use App\Platform\Modules\ModuleMetadataReader;
use App\Platform\Modules\OmniManifestRegistry;
use App\Platform\Modules\PwaManifestRegistry;
use App\Platform\Modules\SettingsRegistry;
use App\Platform\Modules\ShortcutRegistry;
use App\Platform\Modules\TableRegistry;
use App\Platform\Modules\UiKitRegistry;
use App\Platform\Modules\VoiceManifestRegistry;
use App\Platform\Workflows\WorkflowDefinitionRegistry;
use App\Support\FeatureRegistry;
use App\Tenancy\CurrentTenant;
use App\Tenancy\TenantResolver;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\PanelRegistry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module as ModuleFacade;
use Nwidart\Modules\Module;

/**
 * Bootstraps the Titan module layer.
 *
 * Responsibilities:
 * - Discover and register enabled module manifests.
 * - Expose the module registry singleton so other providers can consume it.
 * - Auto-inject enabled module Filament plugins into the appropriate panels.
 * - Must boot before AI and security providers (see bootstrap/providers.php).
 */
class TitanModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleMetadataReader::class, fn ($app) => new ModuleMetadataReader($app['log']));
        $this->app->singleton(ManifestLoader::class, fn ($app) => new ManifestLoader($app['log']));
        $this->app->singleton(BlueprintManifestLoader::class, fn ($app) => new BlueprintManifestLoader($app['log']));
        $this->app->singleton(ModuleKernel::class, fn ($app) => new ModuleKernel(
            $app->make(ModuleMetadataReader::class),
            $app->make(ManifestLoader::class),
            $app->make(BlueprintManifestLoader::class),
            $app['log'],
        ));

        // Bind a module-registry singleton so dependent providers can resolve
        // the enabled-module list without circular boot-order issues.
        $this->app->singletonIf('titan.modules', function ($app) {
            $paths = config('titan-modules.path', 'Modules');

            return $app->make(ModuleKernel::class)->discover($paths);
        });
        $this->app->singletonIf('titan.features', fn () => new FeatureRegistry());
        $this->app->singletonIf('titan.module_boot_failures', fn () => collect());

        // Tenancy layer — available throughout the container.
        $this->app->singleton(TenantResolver::class);
        $this->app->singleton(CurrentTenant::class);

        $this->app->singletonIf(AutomationRegistry::class);
        $this->app->singleton(FilamentRegistry::class);
        $this->app->singleton(WorkflowDefinitionRegistry::class);
        $this->app->singleton(AIManifestRegistry::class);
        $this->app->singleton(BlueprintAIManifestRegistry::class);
        $this->app->singleton(PwaManifestRegistry::class);
        $this->app->singleton(ChannelManifestRegistry::class);
        $this->app->singleton(OmniManifestRegistry::class);
        $this->app->singleton(VoiceManifestRegistry::class);
        $this->app->singleton(UiKitRegistry::class);
        $this->app->singleton(DashboardRegistry::class);
        $this->app->singleton(TableRegistry::class);
        $this->app->singleton(ShortcutRegistry::class);
        $this->app->singleton(SettingsRegistry::class);
        $this->app->singleton(ModuleManifestRegistryLoader::class);
    }

    public function boot(): void
    {
        /** @var ModuleManifestRegistryLoader $registryLoader */
        $registryLoader = $this->app->make(ModuleManifestRegistryLoader::class);
        $registryLoader->load(base_path(config('titan-modules.path', 'Modules')));

        if (class_exists(Module::class) && class_exists(ModuleFacade::class)) {
            $this->discoverAndBootEnabledModules();
        }

        // Guard: only inject when both Filament and nwidart/laravel-modules are present.
        if (! class_exists(PanelRegistry::class) || ! class_exists(Module::class)) {
            return;
        }

        // Hook into the PanelRegistry resolution so that panel objects exist
        // before we attempt to inject plugins.  All PanelProvider::register()
        // closures are also deferred to this same resolving callback, and they
        // were registered earlier (in register()), so they fire first.
        $this->app->resolving(PanelRegistry::class, function (PanelRegistry $registry): void {
            $this->injectModuleFilamentPlugins($registry);
        });
    }

    protected function discoverAndBootEnabledModules(): void
    {
        // Controlled by config/titan-modules.php ('discovery.enabled').
        if (! config('titan-modules.discovery.enabled', true)) {
            return;
        }

        /** @var array<string, Module> $enabledModules */
        $enabledModules = ModuleFacade::allEnabled();
        // Replace the bootstrap-time empty default with the discovered enabled modules.
        $this->app->instance('titan.modules', $enabledModules);

        foreach ($enabledModules as $module) {
            if (! $module instanceof Module) {
                continue;
            }

            $this->registerDeclaredModuleProviders($module);
            $this->registerDeclaredModuleFeatures($module);
        }
    }

    protected function registerDeclaredModuleProviders(Module $module): void
    {
        $providers = $module->get('providers') ?? [];

        if (! is_array($providers)) {
            return;
        }

        foreach ($providers as $providerClass) {
            if (! is_string($providerClass) || trim($providerClass) === '') {
                Log::warning('Skipping invalid module provider entry in manifest.', [
                    'module' => $module->getName(),
                    'provider' => $providerClass,
                ]);
                continue;
            }

            try {
                $this->app->register($providerClass);
            } catch (\Throwable $e) {
                $this->handleModuleProviderBootFailure($module->getName(), $providerClass, $e);
            }
        }
    }

    protected function registerDeclaredModuleFeatures(Module $module): void
    {
        /** @var FeatureRegistry $registry */
        $registry = $this->app->make('titan.features');
        $moduleName = $module->getName();

        foreach ($this->normalizeFeatureEntries($module->get('capabilities') ?? []) as $key => $value) {
            $registry->register($key, $value, $moduleName);
        }

        foreach ($this->normalizeFeatureEntries($module->get('features') ?? []) as $key => $value) {
            $registry->register($key, $value, $moduleName);
        }
    }

    /**
     * @param  mixed  $entries
     * @return array<string, mixed>
     */
    protected function normalizeFeatureEntries(mixed $entries): array
    {
        if (! is_array($entries)) {
            return [];
        }

        $normalized = [];

        foreach ($entries as $key => $value) {
            if (is_int($key)) {
                if (is_string($value) && trim($value) !== '') {
                    $normalized[$value] = true;
                }

                continue;
            }

            if (is_string($key) && trim($key) !== '') {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }

    protected function handleModuleProviderBootFailure(string $moduleName, string $providerClass, \Throwable $exception): void
    {
        // Controlled by config/titan-modules.php ('safe_boot').
        if (! config('titan-modules.safe_boot', true)) {
            throw $exception;
        }

        $message = "Skipping module provider [{$providerClass}] for module [{$moduleName}] due to boot failure.";
        Log::warning($message, [
            'module' => $moduleName,
            'provider' => $providerClass,
            'exception' => $exception::class,
            'error' => $exception->getMessage(),
        ]);

        $failure = [
            'module' => $moduleName,
            'provider' => $providerClass,
            'error' => $exception->getMessage(),
        ];

        $failures = $this->app->make('titan.module_boot_failures');
        if (! $failures instanceof Collection) {
            Log::warning('Unable to record module boot failure: invalid failure registry binding.', [
                'module' => $moduleName,
                'provider' => $providerClass,
                'registry_type' => get_debug_type($failures),
            ]);

            return;
        }

        $failures->push($failure);
    }

    // ─── Plugin injection ─────────────────────────────────────────────────────

    /**
     * Iterate all enabled nwidart modules and register any discovered Filament
     * plugins with the panel(s) declared in the module manifest.
     */
    protected function injectModuleFilamentPlugins(PanelRegistry $registry): void
    {
        /** @var array<string, Module> $enabledModules */
        $enabledModules = \Nwidart\Modules\Facades\Module::allEnabled();

        foreach ($enabledModules as $module) {
            try {
                $this->registerModulePlugin($module, $registry);
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }

    /**
     * Discover and inject the Filament plugin for a single module.
     */
    protected function registerModulePlugin(Module $module, PanelRegistry $registry): void
    {
        $pluginClass = $this->resolvePluginClass($module);

        if ($pluginClass === null) {
            return;
        }

        $panels = $registry->all();

        if (empty($panels)) {
            return;
        }

        // Module manifest may declare a target panel via "filament_panel": "<id>".
        /** @var string|null $panelId */
        $panelId = $module->get('filament_panel');

        if ($panelId && isset($panels[$panelId])) {
            $this->injectPluginIntoPanel($panels[$panelId], $pluginClass);
        } else {
            // Fall back to the default panel.
            try {
                $defaultPanel = $registry->getDefault();
                $this->injectPluginIntoPanel($defaultPanel, $pluginClass);
            } catch (\Throwable) {
                // No default panel configured – skip silently.
            }
        }
    }

    /**
     * Return the fully-qualified plugin class for the module, or null if none
     * can be found.
     *
     * Discovery order:
     *  1. Explicit "filament.plugin" key in module.json.
     *  2. Modules\{Name}\Filament\Plugin\{Name}Plugin  (canonical location)
     *  3. Modules\{Name}\Filament\{Name}Plugin          (legacy / flat layout)
     */
    protected function resolvePluginClass(Module $module): ?string
    {
        $name = $module->getName();

        $candidates = [
            "Modules\\{$name}\\Filament\\Plugin\\{$name}Plugin",
            "Modules\\{$name}\\Filament\\{$name}Plugin",
        ];

        // Allow an explicit override in module.json: "filament": { "plugin": "FQCN" }
        $explicit = $module->get('filament.plugin');
        if (is_string($explicit) && $explicit !== '') {
            array_unshift($candidates, $explicit);
        }

        foreach ($candidates as $class) {
            if (class_exists($class) && is_a($class, Plugin::class, true)) {
                return $class;
            }
        }

        return null;
    }

    /**
     * Inject a single plugin into a panel, skipping if already registered.
     *
     * @param  class-string<Plugin>  $pluginClass
     */
    protected function injectPluginIntoPanel(Panel $panel, string $pluginClass): void
    {
        /** @var Plugin $plugin */
        $plugin = $this->app->make($pluginClass);

        if ($panel->hasPlugin($plugin->getId())) {
            return;
        }

        $panel->plugin($plugin);
    }
}
