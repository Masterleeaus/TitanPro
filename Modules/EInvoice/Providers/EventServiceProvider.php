<?php
namespace Modules\EInvoice\Providers;
use App\Events\NewCompanyCreatedEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\EInvoice\Events\InvoiceBecameOverdue;
use Modules\EInvoice\Events\InvoiceCreated;
use Modules\EInvoice\Listeners\AutoSendInvoiceOnCreated;
use Modules\EInvoice\Listeners\CompanyCreatedListener;
use Modules\EInvoice\Listeners\RunFollowupOnOverdueInvoice;
class EventServiceProvider extends ServiceProvider { protected $listen=[NewCompanyCreatedEvent::class=>[CompanyCreatedListener::class],InvoiceCreated::class=>[AutoSendInvoiceOnCreated::class],InvoiceBecameOverdue::class=>[RunFollowupOnOverdueInvoice::class]]; protected $observers=[]; }
