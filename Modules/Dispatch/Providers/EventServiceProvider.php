<?php

declare(strict_types=1);

namespace Modules\Dispatch\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Dispatch\Events\Domain\DispatchStatusChanged;
use Modules\Dispatch\Events\Domain\WorkOrderScheduled;
use Modules\Dispatch\Listeners\Domain\WriteDispatchTimelineEntry;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WorkOrderScheduled::class => [WriteDispatchTimelineEntry::class],
        DispatchStatusChanged::class => [WriteDispatchTimelineEntry::class],
    ];
}
