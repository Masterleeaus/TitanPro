<?php

declare(strict_types=1);

namespace Modules\Budgeting\Actions\Receipts;

use Modules\Budgeting\Contracts\Services\ReceiptsServiceContract;
use Modules\Budgeting\Models\Receipt;

class MatchReceiptToExpenseAction
{
    public function __construct(protected ReceiptsServiceContract $service) {}

    public function handle(Receipt $receipt, int $expenseId): Receipt
    {
        return $this->service->matchToExpense($receipt, $expenseId);
    }
}
