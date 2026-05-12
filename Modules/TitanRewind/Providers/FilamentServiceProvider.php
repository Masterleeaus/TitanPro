<?php

namespace Modules\TitanRewind\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Placeholder provider to satisfy canonical module structure.
     * Filament plugin registration is handled via module.json + Titan module loader.
     */
    public function register(): void {}

    public function boot(): void {}
}
