<?php

namespace Modules\Payroll\Contracts\Services;

interface PayrollSummaryServiceContract
{
    public function summarize(array $filters = []): array;
}
