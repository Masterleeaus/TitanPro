<?php

namespace Modules\Accountings\Listeners;

use Modules\Accountings\Services\AccountingIntegrationService;

class PostJournalWhenInvoiceSent
{
    public function __construct(private readonly AccountingIntegrationService $accounting) {}

    public function handle($event): void
    {
        $invoice = $event->invoice;
        $this->accounting->postIssuedInvoice(['company_id' => $invoice->company_id ?? null, 'invoice_id' => $invoice->id ?? null, 'amount' => $invoice->total ?? $invoice->amount_due ?? 0, 'description' => 'Invoice issued: '.($invoice->invoice_number ?? $invoice->id)]);
    }
}
