<?php

namespace Modules\InstantAds\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\InstantAds\Events\AdCreativeGenerated;
use Modules\InstantAds\Listeners\EmitAdCreativeGeneratedSignal;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AdCreativeGenerated::class => [
            EmitAdCreativeGeneratedSignal::class,
        ],
    ];
}
