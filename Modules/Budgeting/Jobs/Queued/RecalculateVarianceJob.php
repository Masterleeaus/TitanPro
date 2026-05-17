<?php

declare(strict_types=1);

namespace Modules\Budgeting\Jobs\Queued;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Budgeting\Contracts\Services\VarianceServiceContract;
use Modules\Budgeting\Models\BudgetActual;

class RecalculateVarianceJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly int $companyId) {}

    public function handle(VarianceServiceContract $service): void
    {
        BudgetActual::query()
            ->where('company_id', $this->companyId)
            ->each(fn (BudgetActual $actual) => $service->calculate($actual));
    }
}
