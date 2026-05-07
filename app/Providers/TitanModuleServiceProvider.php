<?php

namespace App\Providers;

use App\Support\FeatureRegistry;
use App\Tenancy\CurrentTenant;
use App\Tenancy\TenantResolver;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\PanelRegistry;
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
        // Bind a module-registry singleton so dependent providers can resolve
        // the enabled-module list without circular boot-order issues.
        $this->app->singletonIf('titan.modules', fn () => []);
        $this->app->singletonIf('titan.features', fn () => new FeatureRegistry());
        $this->app->singletonIf('titan.module_boot_failures', fn () => []);

        // Tenancy layer — available throughout the container.
        $this->app->singleton(TenantResolver::class);
        $this->app->singleton(CurrentTenant::class);
    }

    public function boot(): void
    {
        if (class_exists(Module::class) && class_exists(ModuleFacade::class)) {
            $this->discoverAndBootEnabledModules();
        }

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
        if (! config('titan-modules.discovery.enabled', true)) {
            return;
        }

        /** @var array<string, Module> $enabledModules */
        $enabledModules = ModuleFacade::allEnabled();
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
        $message = "Skipping module provider [{$providerClass}] for module [{$moduleName}] due to boot failure.";
        Log::warning($message, [
            'module' => $moduleName,
            'provider' => $providerClass,
            'exception' => $exception::class,
            'error' => $exception->getMessage(),
        ]);

        $failures = $this->app->make('titan.module_boot_failures');
        if (! is_array($failures)) {
            $failures = [];
        }

        $failures[] = [
            'module' => $moduleName,
            'provider' => $providerClass,
            'error' => $exception->getMessage(),
        ];
        $this->app->instance('titan.module_boot_failures', $failures);

        if (! config('titan-modules.safe_boot', true)) {
            throw $exception;
        }
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
