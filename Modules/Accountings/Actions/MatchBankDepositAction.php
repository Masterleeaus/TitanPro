<?php

namespace Modules\Accountings\Actions;

class MatchBankDepositAction
{
    public function execute(array $deposit, array $candidateInvoices): array
    {
        foreach ($candidateInvoices as $invoice) {
            $referenceMatch = isset($deposit['reference'], $invoice['reference']) && str_contains($deposit['reference'], $invoice['reference']);
            $amountMatch = (float)($deposit['amount'] ?? 0) === (float)($invoice['amount_due'] ?? $invoice['amount'] ?? 0);
            if ($referenceMatch && $amountMatch) {
                return ['status' => 'matched', 'invoice' => $invoice, 'confidence' => 0.98];
            }
        }
        return ['status' => 'pending_review', 'confidence' => 0.0];
    }
}
