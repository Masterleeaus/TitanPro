<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoiceViewed;

class RecordInvoiceViewAction
{
    public function execute(Invoice $invoice, array $options = []): void
    {
        event(new InvoiceViewed($invoice, [
            'company_id'  => $invoice->company_id,
            'actor_id'    => $options['actor_id'] ?? null,
            'occurred_at' => now()->toIso8601String(),
            'channel'     => $options['channel'] ?? 'web',
        ]));
    }
}
