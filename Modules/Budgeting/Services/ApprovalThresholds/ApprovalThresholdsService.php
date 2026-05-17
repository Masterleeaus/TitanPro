<?php

declare(strict_types=1);

namespace Modules\Budgeting\Services\ApprovalThresholds;

use Modules\Budgeting\Contracts\Services\ApprovalThresholdsServiceContract;
use Modules\Budgeting\Models\ApprovalThreshold;
use Modules\Budgeting\Models\Expense;

class ApprovalThresholdsService implements ApprovalThresholdsServiceContract
{
    public function __construct(protected ApprovalThreshold $model) {}

    public function evaluate(Expense $expense): ?ApprovalThreshold
    {
        return $this->model->newQuery()
            ->where('company_id', $expense->company_id)
            ->where('min_amount', '<=', $expense->amount)
            ->where(fn ($q) => $q
                ->whereNull('max_amount')
                ->orWhere('max_amount', '>=', $expense->amount)
            )
            ->orderBy('min_amount', 'desc')
            ->first();
    }

    public function resolveChain(ApprovalThreshold $threshold): array
    {
        $chain = $threshold->chain ?? [];

        if (empty($chain)) {
            return [['role' => $threshold->approver_role, 'escalation_hours' => $threshold->escalation_hours]];
        }

        return $chain;
    }
}
