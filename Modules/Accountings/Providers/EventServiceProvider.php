<?php
namespace Modules\Accountings\Providers;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Accountings\Listeners\PostAccountingOnInvoiceSent;
use Modules\EInvoice\Events\InvoiceSent;
class EventServiceProvider extends ServiceProvider { protected $listen=[InvoiceSent::class=>[PostAccountingOnInvoiceSent::class]]; protected $observers=[]; }
