<?php

namespace Modules\Accountings\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Compatibility provider for hosts that auto-load module providers listed in module.json.
 *
 * The imported Accountings module currently ships classic controllers/views rather than
 * Filament resources. Keeping this provider present prevents provider-resolution failures
 * while leaving room for Filament registration in the host app.
 */
class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Intentionally empty: host applications may bind Filament panels/resources here.
    }

    public function boot(): void
    {
        // Intentionally empty.
    }
}
