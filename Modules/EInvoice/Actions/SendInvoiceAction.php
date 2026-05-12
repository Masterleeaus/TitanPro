<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoiceSent;

class SendInvoiceAction
{
    public function execute(Invoice $invoice, array $options = []): array
    {
        $context = array_merge([
            'company_id'  => $invoice->company_id,
            'actor_id'    => $options['actor_id'] ?? null,
            'occurred_at' => now()->toIso8601String(),
        ], $options);

        event(new InvoiceSent($invoice, $context));

        return [
            'invoice'             => $invoice,
            'status'              => 'sent',
            'payment_owner'       => 'external_zeropay_system',
            'payment_execution'   => 'not_implemented_in_titan_money',
        ];
    }
}
