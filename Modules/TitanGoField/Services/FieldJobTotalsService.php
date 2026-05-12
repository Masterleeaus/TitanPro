<?php

namespace Modules\TitanGoField\Services;

use Modules\TitanGoField\Models\FieldJob;

class FieldJobTotalsService
{
    public function recalculate(FieldJob $job): array
    {
        $partsTotal = $job->serviceParts()
            ->selectRaw('SUM(amount) as total')
            ->value('total') ?? 0;

        $tasksTotal = $job->serviceTasks()
            ->selectRaw('SUM(total) as total')
            ->value('total') ?? 0;

        $partUsagesTotal = $job->partUsages()
            ->selectRaw('SUM(qty * unit_price) as total')
            ->value('total') ?? 0;

        return [
            'parts_total'       => round((float) $partsTotal, 2),
            'tasks_total'       => round((float) $tasksTotal, 2),
            'part_usages_total' => round((float) $partUsagesTotal, 2),
            'grand_total'       => round((float) $partsTotal + (float) $tasksTotal + (float) $partUsagesTotal, 2),
        ];
    }
}
