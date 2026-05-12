<?php

namespace Modules\TitanGoField\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Filament panel registration is handled by TitanGoFieldPlugin.
        // Assets are registered here if required.
    }
}
