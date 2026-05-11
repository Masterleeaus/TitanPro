<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Registry;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts\PluginInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * TitanZeroChat PluginRegistry
 *
 * Central registry for all TitanZeroChat plugins.
 * The TitanZeroChatServiceProvider instantiates this and boots
 * all registered plugins at the appropriate Laravel lifecycle phase.
 *
 * ─── HOW TO REGISTER A PLUGIN ────────────────────────────────────
 *
 * Option A — In TitanZeroChatServiceProvider (built-in plugins):
 *   $registry->register(new MyPlugin());
 *
 * Option B — From any other ServiceProvider (third-party add-ons):
 *   app(PluginRegistry::class)->register(new MyPlugin());
 *
 * Option C — Via config (auto-discovery):
 *   Add class string to config/titanzero-chat.php under 'plugins'
 *   The registry will instantiate and register it automatically.
 *
 * ─── UPGRADE FLOW ─────────────────────────────────────────────────
 *
 * To upgrade a plugin:
 *   1. Drop the new plugin class into Plugins/Plugins/
 *   2. Its migrations() path points to a migrations folder
 *   3. Run: php artisan migrate
 *   4. Clear caches: php artisan optimize:clear
 *   Done. No core files changed.
 *
 * To disable a plugin without removing it:
 *   Set its feature flag in the settings table to 0.
 *   The registry skips routes/views/commands for disabled plugins.
 */
class PluginRegistry
{
    /** @var PluginInterface[] */
    protected array $plugins = [];

    /** @var string[] Slugs of plugins that failed to boot */
    protected array $failed = [];

    public function __construct(protected Application $app)
    {
    }

    // ─────────────────────────────────────────────────────────────────
    // Registration
    // ─────────────────────────────────────────────────────────────────

    /**
     * Register a plugin instance.
     *
     * @throws InvalidArgumentException if id() is not unique
     */
    public function register(PluginInterface $plugin): static
    {
        $id = $plugin->id();

        if (isset($this->plugins[$id])) {
            throw new InvalidArgumentException("TitanZeroChat: Plugin [{$id}] is already registered.");
        }

        $this->plugins[$id] = $plugin;

        return $this;
    }

    /**
     * Register multiple plugins at once.
     *
     * @param  PluginInterface[]  $plugins
     */
    public function registerMany(array $plugins): static
    {
        foreach ($plugins as $plugin) {
            $this->register($plugin);
        }

        return $this;
    }

    /**
     * Auto-discover plugins from config('titan_operator.zero-chat.plugins').
     * Each entry should be a fully-qualified class string.
     */
    public function discoverFromConfig(): static
    {
        $classes = config('titan_operator.zero-chat.plugins', []);

        foreach ($classes as $class) {
            try {
                $this->register($this->app->make($class));
            } catch (\Throwable $e) {
                Log::error("TitanZeroChat: Failed to register plugin [{$class}]", [
                    'error' => $e->getMessage(),
                ]);
                $this->failed[] = $class;
            }
        }

        return $this;
    }

    // ─────────────────────────────────────────────────────────────────
    // Boot Phases (called by TitanZeroChatServiceProvider)
    // ─────────────────────────────────────────────────────────────────

    /**
     * Phase 1 — Register phase (before boot).
     * Call plugin->register() for ALL plugins (enabled and disabled).
     * This allows bindings to be in place before routes/views boot.
     */
    public function bootRegister(): void
    {
        foreach ($this->plugins as $plugin) {
            try {
                $plugin->register();
            } catch (\Throwable $e) {
                $this->recordFailure($plugin, 'register', $e);
            }
        }
    }

    /**
     * Phase 2 — Routes.
     * Only called for enabled plugins.
     */
    public function bootRoutes(Router $router): void
    {
        foreach ($this->enabledPlugins() as $plugin) {
            try {
                $plugin->routes($router);
            } catch (\Throwable $e) {
                $this->recordFailure($plugin, 'routes', $e);
            }
        }
    }

    /**
     * Phase 3 — Views.
     * Only called for enabled plugins.
     */
    public function bootViews(): void
    {
        foreach ($this->enabledPlugins() as $plugin) {
            try {
                foreach ($plugin->views() as $namespace => $path) {
                    $this->app['view']->addNamespace($namespace, $path);
                }
            } catch (\Throwable $e) {
                $this->recordFailure($plugin, 'views', $e);
            }
        }
    }

    /**
     * Phase 4 — Migrations.
     * Registers migration paths for ALL plugins (enabled and disabled).
     * Disabled plugins still need their schema in place.
     */
    public function bootMigrations(): void
    {
        foreach ($this->plugins as $plugin) {
            try {
                if ($path = $plugin->migrations()) {
                    $this->app['migrator']->path($path);
                }
            } catch (\Throwable $e) {
                $this->recordFailure($plugin, 'migrations', $e);
            }
        }
    }

    /**
     * Phase 5 — Assets (publishable files).
     * Only called for enabled plugins.
     */
    public function bootAssets(): void
    {
        foreach ($this->enabledPlugins() as $plugin) {
            try {
                foreach ($plugin->assets() as $tag => $paths) {
                    app()->afterResolving('publishing', fn() => null); // no-op hook
                    // Actual publish registered via ServiceProvider->publishes()
                    // This is handled in TitanZeroChatServiceProvider::publishPluginAssets()
                }
            } catch (\Throwable $e) {
                $this->recordFailure($plugin, 'assets', $e);
            }
        }
    }

    /**
     * Phase 6 — Artisan commands.
     * Only called when running in console.
     *
     * @return array<class-string> Flat list of all command classes from enabled plugins
     */
    public function collectCommands(): array
    {
        $commands = [];

        foreach ($this->enabledPlugins() as $plugin) {
            try {
                array_push($commands, ...$plugin->commands());
            } catch (\Throwable $e) {
                $this->recordFailure($plugin, 'commands', $e);
            }
        }

        return array_unique($commands);
    }

    /**
     * Phase 7 — Default settings seeding.
     * Inserts missing settings into the settings table without overwriting existing values.
     * Called once on first boot after migration.
     */
    public function seedSettings(): void
    {
        if (!app()->runningInConsole() && !app()->isProduction()) {
            return;
        }

        foreach ($this->plugins as $plugin) {
            try {
                foreach ($plugin->settings() as $key => $default) {
                    // Only insert if the key doesn't already exist
                    \DB::table('settings')->insertOrIgnore([
                        'key'        => $key,
                        'value'      => $default,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                // Settings table may not exist yet — safe to silently skip
                Log::debug("TitanZeroChat: Settings seed skipped for plugin [{$plugin->id()}]: " . $e->getMessage());
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // Queries
    // ─────────────────────────────────────────────────────────────────

    /** @return PluginInterface[] All registered plugins (including disabled) */
    public function all(): array
    {
        return $this->plugins;
    }

    /** @return PluginInterface[] Only plugins where enabled() === true */
    public function enabledPlugins(): array
    {
        return array_filter($this->plugins, fn(PluginInterface $p) => $p->enabled());
    }

    /** @return PluginInterface[] Only plugins where enabled() === false */
    public function disabledPlugins(): array
    {
        return array_filter($this->plugins, fn(PluginInterface $p) => !$p->enabled());
    }

    public function has(string $id): bool
    {
        return isset($this->plugins[$id]);
    }

    public function get(string $id): ?PluginInterface
    {
        return $this->plugins[$id] ?? null;
    }

    public function isEnabled(string $id): bool
    {
        return isset($this->plugins[$id]) && $this->plugins[$id]->enabled();
    }

    public function failed(): array
    {
        return $this->failed;
    }

    /**
     * Build a serialisable plugin manifest for dashboard/debug output.
     *
     * @return array<int, array<string, mixed>>
     */
    public function manifest(): array
    {
        $failed = $this->failed();

        return array_values(array_map(function (PluginInterface $plugin) use ($failed) {
            return [
                'id' => $plugin->id(),
                'label' => $plugin->label(),
                'enabled' => $plugin->enabled(),
                'failed' => in_array($plugin->id(), $failed, true),
                'has_migrations' => (bool) $plugin->migrations(),
                'view_namespaces' => array_keys($plugin->views()),
                'asset_tags' => array_keys($plugin->assets()),
                'command_count' => count($plugin->commands()),
                'settings_keys' => array_keys($plugin->settings()),
            ];
        }, $this->plugins));
    }

    // ─────────────────────────────────────────────────────────────────
    // Internals
    // ─────────────────────────────────────────────────────────────────

    protected function recordFailure(PluginInterface $plugin, string $phase, \Throwable $e): void
    {
        $this->failed[] = $plugin->id();
        Log::error("TitanZeroChat: Plugin [{$plugin->id()}] failed at phase [{$phase}]", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
