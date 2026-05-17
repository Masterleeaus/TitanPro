<?php

namespace Modules\Payroll\Contracts\Workflows;

use Modules\Payroll\Entities\SalarySlip;

interface PayrollWorkflowContract
{
    public function submit(SalarySlip $slip): SalarySlip;
    public function approve(SalarySlip $slip, int $approverId): SalarySlip;
    public function reject(SalarySlip $slip, int $approverId, ?string $reason = null): SalarySlip;
    public function markPaid(SalarySlip $slip, array $paymentMeta = []): SalarySlip;
}
