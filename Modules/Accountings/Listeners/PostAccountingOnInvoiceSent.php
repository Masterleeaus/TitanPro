<?php
namespace Modules\Accountings\Listeners;
use Modules\Accountings\Actions\PostGstForInvoiceAction;
use Modules\Accountings\Actions\PostInvoiceJournalAction;
use Modules\EInvoice\Events\InvoiceSent;
class PostAccountingOnInvoiceSent
{
    public function __construct(
        protected PostInvoiceJournalAction $postInvoiceJournal,
        protected PostGstForInvoiceAction $postGstForInvoice,
    ) {}

    public function handle(InvoiceSent $event): void
    {
        $this->postInvoiceJournal->execute([
            'company_id' => $event->context['company_id'] ?? $event->invoice->company_id ?? null,
            'invoice_id' => $event->invoice->id ?? null,
            'reference' => $event->invoice->invoice_number ?? null,
            'amount' => $event->invoice->grand_total ?? $event->invoice->total ?? null,
            'description' => 'Invoice sent journal posting',
        ]);

        $this->postGstForInvoice->execute($event->invoice);
    }
}
