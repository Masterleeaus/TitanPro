<?php

namespace Modules\JobManager\Services;



namespace ModulesJobManagerServices;


namespace Modules\JobManager\Services;

use Modules\JobManager\Entities\{WorkOrder, WOServiceTask, WOServicePart};

class WorkOrderTotals
{
    public static function recalc(int $workOrderId): void
    {
        $tasks = WOServiceTask::where('work_order_id', $workOrderId)->get();
        $parts = WOServicePart::where('work_order_id', $workOrderId)->get();

        $tasksTotal = $tasks->sum(fn($t) => (float) $t->total);
        $partsTotal = $parts->sum(fn($p) => (float) $p->total);
        $grand = $tasksTotal + $partsTotal;

        if ($wo = WorkOrder::find($workOrderId)) {
            $wo->total_estimate = $grand;
            $wo->save();
        }
    }
}
