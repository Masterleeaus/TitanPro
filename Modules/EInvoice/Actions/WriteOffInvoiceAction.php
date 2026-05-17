<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoiceWrittenOff;

class WriteOffInvoiceAction
{
    public function execute(Invoice $invoice, array $options = []): Invoice
    {
        $invoice->status = 'written_off';
        $invoice->save();

        event(new InvoiceWrittenOff($invoice, [
            'company_id'  => $invoice->company_id,
            'actor_id'    => $options['actor_id'] ?? null,
            'occurred_at' => now()->toIso8601String(),
            'reason'      => $options['reason'] ?? null,
        ]));

        return $invoice;
    }
}
