<?php

namespace Modules\Payroll\Contracts\Services;

interface PayrollReconciliationServiceContract
{
    public function reconcile(array $expected, array $actual): array;
}
