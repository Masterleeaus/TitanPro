<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * ManifestServiceProvider exposes the module's manifest to Titan core services.
 *
 * This provider can be used to register additional manifest data at runtime or
 * perform post-processing on the manifest definitions. Currently a stub to
 * satisfy the blueprint contract.
 */
class ManifestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Optionally register manifest modifications
    }

    public function boot(): void
    {
        // Boot any manifest-related tasks
    }
}