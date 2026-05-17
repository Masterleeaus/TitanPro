<?php

declare(strict_types=1);

namespace Modules\Budgeting\Events\Domain;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Budgeting\Models\BudgetVariance;

class VarianceDetected
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly BudgetVariance $model) {}
}
