<?php

namespace Modules\Payroll\Automation\Rules;

class PayrollVarianceRule
{
    public function passes(float $current, float $previous, float $thresholdPercent = 10.0): bool
    {
        if ($previous <= 0) { return true; }
        return abs((($current - $previous) / $previous) * 100) <= $thresholdPercent;
    }
}
