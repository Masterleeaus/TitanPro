<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoiceCreated;

class CreateInvoiceAction
{
    public function execute(array $data): Invoice
    {
        $invoice = Invoice::create($data);

        event(new InvoiceCreated($invoice, [
            'company_id'  => $invoice->company_id,
            'actor_id'    => $data['actor_id'] ?? null,
            'occurred_at' => now()->toIso8601String(),
        ]));

        return $invoice;
    }
}
