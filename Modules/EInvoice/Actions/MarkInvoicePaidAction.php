<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoicePaid;
use Modules\EInvoice\Events\InvoiceClosed;

class MarkInvoicePaidAction
{
    public function execute(Invoice $invoice, array $options = []): Invoice
    {
        $invoice->status = 'paid';
        $invoice->save();

        $context = [
            'company_id'  => $invoice->company_id,
            'actor_id'    => $options['actor_id'] ?? null,
            'occurred_at' => now()->toIso8601String(),
        ];

        event(new InvoicePaid($invoice, $context));
        event(new InvoiceClosed($invoice, $context));

        return $invoice;
    }
}
