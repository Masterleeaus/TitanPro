<?php

namespace Modules\EInvoice\Services;

use Modules\EInvoice\Actions\SendInvoiceAction;
use Modules\EInvoice\Entities\Invoice;

class InvoiceAutomationService
{
    public function __construct(private readonly SendInvoiceAction $sendInvoice) {}

    public function autoSend(Invoice $invoice, array $context = []): array
    {
        return $this->sendInvoice->execute($invoice, $context);
    }
}
