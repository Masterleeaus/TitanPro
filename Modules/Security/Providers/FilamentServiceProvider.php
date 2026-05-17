<?php

namespace Modules\Security\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Filament resources are auto-discoverable by host panel configuration.
    }
}
