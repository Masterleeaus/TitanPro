<?php

declare(strict_types=1);

namespace Modules\Budgeting\Events\Domain;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Budgeting\Models\ReimbursementBatch;

class ReimbursementBatchPaid
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly ReimbursementBatch $model) {}
}
