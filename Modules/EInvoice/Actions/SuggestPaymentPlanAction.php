<?php
namespace Modules\EInvoice\Actions;
use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoicePaymentPlanSuggested;
class SuggestPaymentPlanAction {
    public function execute(Invoice $invoice, int $instalments = 3): array
    {
        $balance = (float) ($invoice->balance_due ?? $invoice->total ?? 0);
        $instalments = max(1, min(12, $instalments));
        $result = [
            'invoice_id'   => $invoice->id ?? null,
            'instalments'  => $instalments,
            'amount_each'  => round($balance / $instalments, 2),
            'note'         => 'Suggested only. Payment execution remains in ZeroPay.',
        ];

        event(new InvoicePaymentPlanSuggested($invoice, [
            'company_id'  => $invoice->company_id,
            'actor_id'    => null,
            'occurred_at' => now()->toIso8601String(),
            'instalments' => $instalments,
        ]));

        return $result;
    }
}
