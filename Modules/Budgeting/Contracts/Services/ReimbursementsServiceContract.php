<?php

declare(strict_types=1);

namespace Modules\Budgeting\Contracts\Services;

use Modules\Budgeting\Models\ReimbursementBatch;

interface ReimbursementsServiceContract
{
    public function createBatch(int $companyId, array $expenseIds): ReimbursementBatch;

    public function approveBatch(ReimbursementBatch $batch): ReimbursementBatch;

    public function markPaid(ReimbursementBatch $batch): ReimbursementBatch;
}
