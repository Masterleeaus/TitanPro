<?php

namespace Modules\TitanEchoAssist\Listeners\PortalAutomation;

use App\Models\Invoice;
use Modules\TitanEchoAssist\Services\ChatbotPortalAutomationService;

class HandleInvoiceOverdue
{
    public function __construct(private readonly ChatbotPortalAutomationService $automationService) {}

    public function handle(Invoice $invoice): void
    {
        $invoice->loadMissing('customer');

        $this->automationService->trigger('invoice_overdue', [
            ...$this->automationService->payloadFromCustomer($invoice->organization_id, $invoice->customer_id),
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'balance_due' => $invoice->balance_due,
            'due_at' => $invoice->due_at?->toDateString(),
        ]);
    }
}

