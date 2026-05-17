<?php

namespace Modules\Payroll\Workflows\Definitions;

use Modules\Payroll\Contracts\Workflows\PayrollWorkflowContract;
use Modules\Payroll\Entities\SalarySlip;

class PayrollApprovalWorkflow implements PayrollWorkflowContract
{
    public function submit(SalarySlip $slip): SalarySlip { return $this->transition($slip, 'submitted'); }
    public function approve(SalarySlip $slip, int $approverId): SalarySlip { $slip->approved_by = $approverId; return $this->transition($slip, 'approved'); }
    public function reject(SalarySlip $slip, int $approverId, ?string $reason = null): SalarySlip { $slip->approved_by = $approverId; $slip->rejection_reason = $reason; return $this->transition($slip, 'rejected'); }
    public function markPaid(SalarySlip $slip, array $paymentMeta = []): SalarySlip { $slip->payment_meta = json_encode($paymentMeta); return $this->transition($slip, 'paid'); }
    private function transition(SalarySlip $slip, string $status): SalarySlip { $slip->status = $status; $slip->save(); return $slip->refresh(); }
}
