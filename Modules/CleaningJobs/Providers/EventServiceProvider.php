<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\CleaningJobs\Events\{WorkOrderCreated, WorkOrderUpdated, WorkOrderCompleted};
use Modules\CleaningJobs\Listeners\{SendWorkOrderWebhook, AutoConvertOnCompletion, LogWorkOrderActivity};

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WorkOrderCreated::class => [SendWorkOrderWebhook::class, LogWorkOrderActivity::class],
        WorkOrderUpdated::class => [SendWorkOrderWebhook::class, LogWorkOrderActivity::class],
        WorkOrderCompleted::class => [SendWorkOrderWebhook::class, AutoConvertOnCompletion::class, LogWorkOrderActivity::class],
    ];
}
