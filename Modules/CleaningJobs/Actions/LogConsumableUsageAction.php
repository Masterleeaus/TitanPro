<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\ConsumablesUsed;
use Modules\CleaningJobs\Models\WorkOrder;

class LogConsumableUsageAction
{
    public function handle(WorkOrder $job, float $amount): WorkOrder
    {
        $newCost = (float) ($job->actual_cost ?? 0) + $amount;
        $job->update(['actual_cost' => $newCost]);
        ConsumablesUsed::dispatch($job, 0, $amount);
        return $job;
    }
}
