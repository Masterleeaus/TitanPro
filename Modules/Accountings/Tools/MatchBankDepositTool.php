<?php

namespace Modules\Accountings\Tools;

use Modules\Accountings\Actions\MatchBankDepositAction;

class MatchBankDepositTool
{
    public function __construct(protected MatchBankDepositAction $action) {}

    public function name(): string { return 'accounting.match_reconciliation_record'; }

    public function description(): string { return 'Matches bank deposits to invoices using reference, amount, customer, and timestamp window.'; }

    public function __invoke(array $deposit, array $candidateInvoices = []): array
    {
        return $this->action->execute($deposit, $candidateInvoices);
    }
}
