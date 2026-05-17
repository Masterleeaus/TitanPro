<?php

declare(strict_types=1);

namespace Modules\Budgeting\Actions\Reimbursements;

use Modules\Budgeting\Contracts\Services\ReimbursementsServiceContract;
use Modules\Budgeting\Models\ReimbursementBatch;

class CreateReimbursementBatchAction
{
    public function __construct(protected ReimbursementsServiceContract $service) {}

    public function handle(int $companyId, array $expenseIds): ReimbursementBatch
    {
        return $this->service->createBatch($companyId, $expenseIds);
    }
}
