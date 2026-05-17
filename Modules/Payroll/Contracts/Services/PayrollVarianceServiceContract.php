<?php

namespace Modules\Payroll\Contracts\Services;

interface PayrollVarianceServiceContract
{
    public function compare(array $currentRun, ?array $previousRun = null, array $options = []): array;
}
