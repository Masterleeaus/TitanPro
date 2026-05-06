<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Bootstraps the Titan module-security layer.
 *
 * Responsibilities:
 * - Register per-module permission policies.
 * - Bind security-audit and threat-detection contracts.
 * - Depends on TitanModuleServiceProvider being booted first so that the
 *   module registry is available when policies are resolved.
 */
class TitanModuleSecurityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singletonIf('titan.module.security', fn () => []);
    }

    public function boot(): void
    {
        //
    }
}
