<?php

namespace Modules\TitanRewind\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Accountings\Events\InvoiceJournalPosted;
use Modules\EInvoice\Events\InvoiceSent;
use Modules\TitanRewind\Listeners\CaptureSnapshotListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        InvoiceJournalPosted::class => [
            CaptureSnapshotListener::class,
        ],
        InvoiceSent::class => [
            CaptureSnapshotListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();

        Event::listen('TitanCore.AuditEventLogged', function (...$payload): void {
            app(CaptureSnapshotListener::class)->handleStringEvent('TitanCore.AuditEventLogged', $payload);
        });

        Event::listen('Accountings.JournalPosted', function (...$payload): void {
            app(CaptureSnapshotListener::class)->handleStringEvent('Accountings.JournalPosted', $payload);
        });

        Event::listen('EInvoice.InvoiceSent', function (...$payload): void {
            app(CaptureSnapshotListener::class)->handleStringEvent('EInvoice.InvoiceSent', $payload);
        });
    }
}
