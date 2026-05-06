<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Bootstraps the Titan AI runtime layer.
 *
 * Responsibilities:
 * - Register AI orchestrators and model-router contracts.
 * - Bind the AI tool registry singleton.
 * - Register governance evaluators.
 * - Depends on TitanModuleServiceProvider being booted first.
 */
class TitanAiRuntimeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singletonIf('titan.ai.tools', fn () => []);
    }

    public function boot(): void
    {
        //
    }
}
