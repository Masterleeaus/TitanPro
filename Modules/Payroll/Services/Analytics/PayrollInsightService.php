<?php

namespace Modules\Payroll\Services\Analytics;

use Illuminate\Support\Collection;

class PayrollInsightService
{
    public function summarize(Collection $rows): array
    {
        return ['employees' => $rows->count(), 'gross_total' => round((float) $rows->sum('gross_pay'), 2), 'net_total' => round((float) $rows->sum('net_pay'), 2), 'average_net_pay' => round((float) $rows->avg('net_pay'), 2), 'warnings' => $rows->pluck('warnings')->flatten()->values()->all()];
    }
}
