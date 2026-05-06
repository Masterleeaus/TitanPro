<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Bootstraps the Titan module layer.
 *
 * Responsibilities:
 * - Discover and register enabled module manifests.
 * - Expose the module registry singleton so other providers can consume it.
 * - Must boot before AI and security providers (see bootstrap/providers.php).
 */
class TitanModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind a module-registry singleton so dependent providers can resolve
        // the enabled-module list without circular boot-order issues.
        $this->app->singletonIf('titan.modules', fn () => []);
    }

    public function boot(): void
    {
        //
    }
}
