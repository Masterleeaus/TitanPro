<?php

namespace Modules\Payroll\Services\Analytics;

use Modules\Payroll\Contracts\Services\PayrollVarianceServiceContract;

class PayrollVarianceService implements PayrollVarianceServiceContract
{
    public function compare(array $currentRun, ?array $previousRun = null, array $options = []): array
    {
        $threshold = (float) ($options['threshold_percent'] ?? config('payroll.features.variance_threshold_percent', 10));
        $fields = $options['fields'] ?? ['gross_total', 'net_total', 'processed_count'];
        $variances = [];

        foreach ($fields as $field) {
            $current = (float) ($currentRun[$field] ?? 0);
            $previous = (float) ($previousRun[$field] ?? 0);
            $delta = $current - $previous;
            $percent = $previous == 0.0 ? ($current == 0.0 ? 0.0 : 100.0) : ($delta / $previous) * 100;
            $variances[$field] = [
                'current' => round($current, 2),
                'previous' => round($previous, 2),
                'delta' => round($delta, 2),
                'percent' => round($percent, 2),
                'flagged' => abs($percent) >= $threshold,
            ];
        }

        return [
            'passed' => ! collect($variances)->contains(fn ($row) => $row['flagged']),
            'threshold_percent' => $threshold,
            'variances' => $variances,
        ];
    }
}
