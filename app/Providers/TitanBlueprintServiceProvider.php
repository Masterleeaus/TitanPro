<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Bootstraps the Titan blueprint layer.
 *
 * Responsibilities:
 * - Load and register platform blueprint manifests.
 * - Provide a blueprint registry accessible to other providers and modules.
 */
class TitanBlueprintServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singletonIf('titan.blueprints', fn () => []);
    }

    public function boot(): void
    {
        //
    }
}
