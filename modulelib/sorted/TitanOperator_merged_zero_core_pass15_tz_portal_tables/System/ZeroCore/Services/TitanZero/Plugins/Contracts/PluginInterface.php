<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts;

use Illuminate\Routing\Router;

/**
 * TitanZeroChat Plugin Contract
 *
 * Every TitanZeroChat plugin implements this interface.
 * The PluginRegistry calls each method at the appropriate boot phase.
 *
 * A plugin is a self-contained feature unit that registers itself with
 * TitanZeroChat core — no core files need to be touched to add, remove,
 * or upgrade a plugin.
 *
 * ┌─────────────────────────────────────────────────────────┐
 * │  PLUGIN LIFECYCLE                                       │
 * │                                                         │
 * │  register()   → bindings, config merges                 │
 * │  routes()     → route definitions on the shared router  │
 * │  migrations() → path to migrations directory            │
 * │  views()      → ['namespace' => 'path']                 │
 * │  assets()     → ['tag' => ['src' => 'dest']]            │
 * │  commands()   → array of Artisan command class strings  │
 * │  settings()   → default setting key/value pairs         │
 * │  id()         → unique string slug e.g. 'tzc-folders'   │
 * │  label()      → human-readable name                     │
 * │  enabled()    → feature flag gate                       │
 * └─────────────────────────────────────────────────────────┘
 */
interface PluginInterface
{
    /**
     * Unique plugin identifier slug.
     * Used for enable/disable toggle in settings table.
     * Convention: 'tzc-{feature}' e.g. 'tzc-folders', 'tzc-canvas'
     */
    public function id(): string;

    /**
     * Human-readable plugin name shown in admin UI.
     */
    public function label(): string;

    /**
     * Whether this plugin is currently active.
     * Typically reads a feature flag from the settings table.
     * Return true to always enable (for core features).
     */
    public function enabled(): bool;

    /**
     * Register bindings, merge config, or bind interfaces.
     * Called during the service container register phase.
     */
    public function register(): void;

    /**
     * Register routes on the shared Laravel router.
     * Middleware and prefix grouping should be done inside this method.
     * Only called if enabled() returns true.
     */
    public function routes(Router $router): void;

    /**
     * Absolute path to this plugin's migrations directory.
     * Return null if the plugin has no migrations.
     *
     * @return string|null
     */
    public function migrations(): ?string;

    /**
     * View namespaces to register.
     * Return an associative array of ['namespace-hint' => '/absolute/path/to/views'].
     * Convention: all plugins share 'titanzero-chat' namespace with subdirectory scoping.
     *
     * @return array<string, string>
     */
    public function views(): array;

    /**
     * Publishable assets.
     * Return ['tag' => ['source/path' => 'destination/path']] or empty array.
     *
     * @return array<string, array<string, string>>
     */
    public function assets(): array;

    /**
     * Artisan command class strings to register.
     * Return empty array if none.
     *
     * @return array<class-string>
     */
    public function commands(): array;

    /**
     * Default settings this plugin requires in the settings table.
     * The registry will insert these with their default values if not present.
     *
     * @return array<string, mixed>  ['setting_key' => 'default_value']
     */
    public function settings(): array;
}
