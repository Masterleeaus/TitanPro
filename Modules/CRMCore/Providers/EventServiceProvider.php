<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\CRMCore\Events\ActivityLogged;
use Modules\CRMCore\Events\ContactCreated;
use Modules\CRMCore\Events\DealLost;
use Modules\CRMCore\Events\DealConvertedToProject;
use Modules\CRMCore\Events\DealWon;
use Modules\CRMCore\Events\LeadScored;
use Modules\CRMCore\Listeners\CreateContactFromLeadConvertedSignal;
use Modules\CRMCore\Listeners\RecordDealConvertedToProject;
use Modules\CRMCore\Listeners\RecordLeadScored;
use Modules\CRMCore\Listeners\SyncContactFromBookingCustomerCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DealConvertedToProject::class => [
            RecordDealConvertedToProject::class,
        ],
        LeadScored::class => [
            RecordLeadScored::class,
        ],
        ContactCreated::class => [],
        DealWon::class => [],
        DealLost::class => [],
        ActivityLogged::class => [],
    ];

    public function boot(): void
    {
        parent::boot();

        Event::listen('TitanLeads::LeadConverted', CreateContactFromLeadConvertedSignal::class);
        Event::listen('BookingModule::CustomerCreated', SyncContactFromBookingCustomerCreated::class);
    }
}
