<?php

declare(strict_types=1);

namespace Modules\Budgeting\Actions\Reimbursements;

use Modules\Budgeting\Contracts\Services\ReimbursementsServiceContract;
use Modules\Budgeting\Models\ReimbursementBatch;

class MarkReimbursementPaidAction
{
    public function __construct(protected ReimbursementsServiceContract $service) {}

    public function handle(ReimbursementBatch $batch): ReimbursementBatch
    {
        return $this->service->markPaid($batch);
    }
}
