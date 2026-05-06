<?php

namespace Modules\EInvoice\Tools;

class ExplainInvoiceTool
{
    public function name(): string { return 'einvoice.explain_invoice'; }
    public function description(): string { return 'Explains invoice line items, due dates, GST/compliance context, and follow-up status. It does not process payments.'; }
    public function handle($invoice): array
    {
        return [
            'invoice_id' => $invoice->id ?? null,
            'amount_due' => $invoice->total ?? $invoice->amount ?? null,
            'due_date' => $invoice->due_date ?? null,
            'payment_execution' => 'external_zeropay_system',
            'message' => 'Titan Money can explain and follow up this invoice. Payment handling is owned by ZeroPay.',
        ];
    }
}
