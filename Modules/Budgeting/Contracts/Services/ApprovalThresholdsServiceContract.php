<?php

declare(strict_types=1);

namespace Modules\Budgeting\Contracts\Services;

use Modules\Budgeting\Models\ApprovalThreshold;
use Modules\Budgeting\Models\Expense;

interface ApprovalThresholdsServiceContract
{
    public function evaluate(Expense $expense): ?ApprovalThreshold;

    public function resolveChain(ApprovalThreshold $threshold): array;
}
