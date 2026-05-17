<?php

namespace Modules\Payroll\AI\Tools;

class PayrollAnomalyTool
{
    public function inspect(array $currentRun, array $previousRun = []): array
    {
        $current = (float) ($currentRun['net_total'] ?? 0);
        $previous = (float) ($previousRun['net_total'] ?? 0);
        $delta = $previous > 0 ? (($current - $previous) / $previous) * 100 : 0.0;
        return ['delta_percent' => round($delta, 2), 'severity' => abs($delta) >= 20 ? 'high' : (abs($delta) >= 10 ? 'medium' : 'low'), 'requires_review' => abs($delta) >= 10];
    }
}
