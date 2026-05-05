<?php

namespace Modules\EInvoice\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Compatibility provider for hosts that auto-load module providers listed in module.json.
 *
 * The EInvoice module uses Blade/DataTables in this package. This no-op provider keeps
 * the AI-native module manifest accurate without crashing apps that expect the provider
 * class to exist.
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
