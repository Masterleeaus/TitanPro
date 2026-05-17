<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoiceVoided;

class VoidInvoiceAction
{
    public function execute(Invoice $invoice, array $options = []): Invoice
    {
        $invoice->status = 'void';
        $invoice->save();

        event(new InvoiceVoided($invoice, [
            'company_id'  => $invoice->company_id,
            'actor_id'    => $options['actor_id'] ?? null,
            'occurred_at' => now()->toIso8601String(),
            'reason'      => $options['reason'] ?? null,
        ]));

        return $invoice;
    }
}
