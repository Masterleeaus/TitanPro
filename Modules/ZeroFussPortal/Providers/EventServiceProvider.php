<?php

namespace Modules\ZeroFussPortal\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\ZeroFussPortal\Listeners\AwardPointsOnPaymentListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'EInvoice.InvoicePaid' => [
            AwardPointsOnPaymentListener::class,
        ],
    ];
}
