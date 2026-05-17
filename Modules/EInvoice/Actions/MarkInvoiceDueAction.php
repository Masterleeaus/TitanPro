<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoiceBecameDue;

class MarkInvoiceDueAction
{
    public function execute(Invoice $invoice, array $options = []): Invoice
    {
        event(new InvoiceBecameDue($invoice, [
            'company_id'  => $invoice->company_id,
            'actor_id'    => $options['actor_id'] ?? null,
            'occurred_at' => now()->toIso8601String(),
        ]));

        return $invoice;
    }
}
