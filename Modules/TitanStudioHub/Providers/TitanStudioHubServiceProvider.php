<?php

namespace Modules\TitanStudioHub\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\TitanStudioHub\Listeners\AddCreativeToBrandAssetLibraryListener;

class TitanStudioHubServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Event::listen('InstantAds::AdCreativeGenerated', AddCreativeToBrandAssetLibraryListener::class);
    }
}
