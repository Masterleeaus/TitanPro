<?php

namespace Modules\Accountings\Actions;

use Illuminate\Support\Facades\Event;
use Modules\Accountings\Events\LedgerAdjustmentSuggested;
use Modules\Accountings\Events\ZeroPayPaymentConfirmed;

class MatchBankDepositAction
{
    public function execute(array $deposit, array $candidateInvoices): array
    {
        foreach ($candidateInvoices as $invoice) {
            $referenceMatch = isset($deposit['reference'], $invoice['reference']) && str_contains($deposit['reference'], $invoice['reference']);
            $amountMatch = (float)($deposit['amount'] ?? 0) === (float)($invoice['amount_due'] ?? $invoice['amount'] ?? 0);
            if ($referenceMatch && $amountMatch) {
                $payload = [
                    'company_id' => $deposit['company_id'] ?? $invoice['company_id'] ?? auth()->user()?->company_id,
                    'actor_id' => auth()->id(),
                    'occurred_at' => now()->toIso8601String(),
                    'deposit' => $deposit,
                    'invoice' => $invoice,
                ];
                Event::dispatch(new ZeroPayPaymentConfirmed($payload));

                return ['status' => 'matched', 'invoice' => $invoice, 'confidence' => 0.98];
            }
        }
        Event::dispatch(new LedgerAdjustmentSuggested([
            'company_id' => $deposit['company_id'] ?? auth()->user()?->company_id,
            'actor_id' => auth()->id(),
            'occurred_at' => now()->toIso8601String(),
            'deposit' => $deposit,
            'candidate_count' => count($candidateInvoices),
        ]));

        return ['status' => 'pending_review', 'confidence' => 0.0];
    }
}
