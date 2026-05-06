<?php

namespace Modules\HRCore\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Compatibility shim for the savannabits/filament-modules auto-discovery mechanism.
 *
 * The savannabits package scans for provider files two directory levels deep under
 * the Modules folder and strips the "app" sub-folder when deriving the PSR-4
 * namespace. HRCore uses the app/ layout, so the real service provider lives at
 * Modules\HRCore\app\Providers\HRCoreServiceProvider, but the auto-discover
 * logic attempts to instantiate Modules\HRCore\Providers\HRCoreServiceProvider.
 *
 * This no-op shim satisfies the class resolution without duplicating provider
 * registration (the real provider is registered via module.json / nwidart).
 */
class HRCoreServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void {}
}
