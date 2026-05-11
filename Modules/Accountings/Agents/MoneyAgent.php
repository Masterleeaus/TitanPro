<?php

namespace Modules\Accountings\Agents;

use Modules\Accountings\Tools\LookupLedgerTool;
use Modules\Accountings\Tools\MatchBankDepositTool;
use Modules\Accountings\Tools\PostInvoiceJournalTool;

class MoneyAgent
{
    public function name(): string
    {
        return 'TitanZero Money';
    }

    public function goal(): string
    {
        return 'Assist platform users with invoicing, accounting, receivables, GST, reconciliation records, and late-invoice follow-up. Payment execution belongs to the external ZeroPay system.';
    }

    public function domains(): array
    {
        return ['invoicing', 'receivables', 'late_followups', 'ledger_posting', 'gst_reporting', 'accounting_reconciliation', 'cashflow'];
    }

    public function tools(): array
    {
        return [LookupLedgerTool::class, PostInvoiceJournalTool::class, MatchBankDepositTool::class];
    }
}
