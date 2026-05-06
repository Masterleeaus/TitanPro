<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Bootstraps the Titan model runtime layer.
 *
 * Responsibilities:
 * - Register model-routing logic and inference-engine bindings.
 * - Provide context-pack and evaluation registries.
 * - Depends on TitanAiRuntimeServiceProvider being registered first.
 */
class TitanModelRuntimeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singletonIf('titan.model.router', fn () => null);
    }

    public function boot(): void
    {
        //
    }
}
