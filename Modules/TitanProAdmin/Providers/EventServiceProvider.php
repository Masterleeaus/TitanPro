<?php

namespace Modules\TitanProAdmin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\TitanProAdmin\Events\ModuleDisabled;
use Modules\TitanProAdmin\Events\ModuleEnabled;
use Modules\TitanProAdmin\Events\TenantSuspended;
use Modules\TitanProAdmin\Listeners\HandleModuleDisabled;
use Modules\TitanProAdmin\Listeners\HandleModuleEnabled;
use Modules\TitanProAdmin\Listeners\HandleTenantSuspended;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TenantSuspended::class => [
            HandleTenantSuspended::class,
        ],
        ModuleEnabled::class => [
            HandleModuleEnabled::class,
        ],
        ModuleDisabled::class => [
            HandleModuleDisabled::class,
        ],
    ];
}
