<?php

namespace Modules\Payroll\Contracts\Services;

interface CleanerShiftReconciliationServiceContract
{
    public function reconcile(array $rosteredShifts, array $paidLines): array;
}
