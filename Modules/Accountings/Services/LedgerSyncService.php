<?php

namespace Modules\Accountings\Services;

use Illuminate\Support\Facades\Event;
use Modules\Accountings\Events\WriteOffPosted;

class LedgerSyncService
{
    public function postInvoiceSent(object|array $invoice, array $context = []): array
    {
        return [
            'type' => 'invoice_sent',
            'invoice_id' => data_get($invoice, 'id'),
            'lines' => [
                ['side' => 'debit', 'account' => 'accounts_receivable', 'amount' => data_get($invoice, 'total', 0)],
                ['side' => 'credit', 'account' => 'revenue', 'amount' => data_get($invoice, 'subtotal', data_get($invoice, 'total', 0))],
                ['side' => 'credit', 'account' => 'gst_payable', 'amount' => data_get($invoice, 'tax_total', 0)],
            ],
            'context' => $context,
        ];
    }

    public function postWriteOff(object|array $invoice, array $context = []): array
    {
        $amount = data_get($invoice, 'balance_due', data_get($invoice, 'total', 0));
        $result = [
            'type' => 'write_off',
            'invoice_id' => data_get($invoice, 'id'),
            'lines' => [
                ['side' => 'debit', 'account' => 'bad_debt_expense', 'amount' => $amount],
                ['side' => 'credit', 'account' => 'accounts_receivable', 'amount' => $amount],
            ],
            'context' => $context,
        ];

        Event::dispatch(new WriteOffPosted([
            'company_id' => data_get($invoice, 'company_id', $context['company_id'] ?? auth()->user()?->company_id),
            'actor_id' => $context['actor_id'] ?? auth()->id(),
            'occurred_at' => now()->toIso8601String(),
        ] + $result));

        return $result;
    }
}
