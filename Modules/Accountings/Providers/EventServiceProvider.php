<?php

namespace Modules\Accountings\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Accountings\Listeners\PostAccountingOnInvoiceSent;
use Modules\EInvoice\Events\InvoiceSent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        InvoiceSent::class => [
            PostAccountingOnInvoiceSent::class,
        ],
    ];
}
