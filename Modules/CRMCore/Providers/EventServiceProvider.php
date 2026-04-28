<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\CRMCore\Events\DealConvertedToProject;
use Modules\CRMCore\Events\LeadScored;
use Modules\CRMCore\Listeners\RecordDealConvertedToProject;
use Modules\CRMCore\Listeners\RecordLeadScored;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DealConvertedToProject::class => [
            RecordDealConvertedToProject::class,
        ],
        LeadScored::class => [
            RecordLeadScored::class,
        ],
    ];
}
