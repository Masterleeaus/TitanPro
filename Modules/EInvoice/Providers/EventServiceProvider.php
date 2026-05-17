<?php
namespace Modules\EInvoice\Providers;
use App\Events\NewCompanyCreatedEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\EInvoice\Events\AiNoteGenerated;
use Modules\EInvoice\Events\InvoiceBecameDue;
use Modules\EInvoice\Events\InvoiceBecameOverdue;
use Modules\EInvoice\Events\InvoiceClosed;
use Modules\EInvoice\Events\InvoiceCreated;
use Modules\EInvoice\Events\InvoiceEscalated;
use Modules\EInvoice\Events\InvoiceExported;
use Modules\EInvoice\Events\InvoicePaid;
use Modules\EInvoice\Events\InvoicePaymentPlanSuggested;
use Modules\EInvoice\Events\InvoiceSent;
use Modules\EInvoice\Events\InvoiceViewed;
use Modules\EInvoice\Events\InvoiceVoided;
use Modules\EInvoice\Events\InvoiceWrittenOff;
use Modules\EInvoice\Events\ZeroPayHandoffInitiated;
use Modules\EInvoice\Listeners\AutoSendInvoiceOnCreated;
use Modules\EInvoice\Listeners\CompanyCreatedListener;
use Modules\EInvoice\Listeners\RunFollowupOnOverdueInvoice;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewCompanyCreatedEvent::class       => [CompanyCreatedListener::class],
        InvoiceCreated::class               => [AutoSendInvoiceOnCreated::class],
        InvoiceBecameOverdue::class         => [RunFollowupOnOverdueInvoice::class],
        InvoiceSent::class                  => [],
        InvoicePaid::class                  => [],
        InvoiceClosed::class                => [],
        InvoiceBecameDue::class             => [],
        InvoiceEscalated::class             => [],
        InvoicePaymentPlanSuggested::class  => [],
        InvoiceViewed::class                => [],
        InvoiceVoided::class                => [],
        InvoiceWrittenOff::class            => [],
        InvoiceExported::class              => [],
        AiNoteGenerated::class              => [],
        ZeroPayHandoffInitiated::class      => [],
    ];

    protected $observers = [];
}
