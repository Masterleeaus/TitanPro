<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoiceSent;

class SendInvoiceAction
{
    public function execute(Invoice $invoice, array $options = []): array
    {
        event(new InvoiceSent($invoice, $options));

        return [
            'invoice' => $invoice,
            'status' => 'sent',
            'payment_owner' => 'external_zeropay_system',
            'payment_execution' => 'not_implemented_in_titan_money',
        ];
    }
}
