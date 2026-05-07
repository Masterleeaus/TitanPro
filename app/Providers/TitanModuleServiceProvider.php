<?php

namespace App\Providers;

use App\Platform\Automation\AutomationRegistry;
use App\Platform\Billing\BillingRegistry;
use App\Platform\Filament\FilamentRegistry;
use App\Platform\Modules\ModuleManifestRegistryLoader;
use App\Platform\Search\SearchRegistry;
use App\Platform\Tenancy\TenancyRegistry;
use App\Platform\Verticals\VerticalPackRegistry;
use App\Platform\Verticals\VerticalResolver;
use App\Platform\Workflows\WorkflowDefinitionRegistry;
use App\Tenancy\CurrentTenant;
use App\Tenancy\TenantResolver;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\PanelRegistry;
use Illuminate\Support\ServiceProvider;
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

        // Tenancy layer — available throughout the container.
        $this->app->singleton(TenantResolver::class);
        $this->app->singleton(CurrentTenant::class);

        $this->app->singletonIf(AutomationRegistry::class);
        $this->app->singleton(BillingRegistry::class);
        $this->app->singleton(FilamentRegistry::class);
        $this->app->singleton(SearchRegistry::class);
        $this->app->singleton(TenancyRegistry::class);
        $this->app->singleton(VerticalPackRegistry::class);
        $this->app->singleton(VerticalResolver::class);
        $this->app->singleton(WorkflowDefinitionRegistry::class);
        $this->app->singleton(ModuleManifestRegistryLoader::class);
    }

    public function boot(): void
    {
        /** @var ModuleManifestRegistryLoader $registryLoader */
        $registryLoader = $this->app->make(ModuleManifestRegistryLoader::class);
        $registryLoader->load(base_path(config('titan-modules.path', 'Modules')));

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
