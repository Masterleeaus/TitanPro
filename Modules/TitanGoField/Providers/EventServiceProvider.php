<?php

namespace Modules\TitanGoField\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\TitanGoField\Events\FieldJobCreated;
use Modules\TitanGoField\Events\FieldJobUpdated;
use Modules\TitanGoField\Events\FieldJobCompleted;
use Modules\TitanGoField\Listeners\LogFieldJobActivity;
use Modules\TitanGoField\Listeners\SendFieldJobWebhook;
use Modules\TitanGoField\Listeners\FieldJobAutoInvoiceListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        FieldJobCreated::class => [
            LogFieldJobActivity::class,
            SendFieldJobWebhook::class,
        ],
        FieldJobUpdated::class => [
            LogFieldJobActivity::class,
        ],
        FieldJobCompleted::class => [
            LogFieldJobActivity::class,
            SendFieldJobWebhook::class,
            FieldJobAutoInvoiceListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
