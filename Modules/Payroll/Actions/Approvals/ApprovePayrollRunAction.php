<?php

namespace Modules\Payroll\Actions\Approvals;

use Modules\Payroll\Contracts\Services\PayrollApprovalServiceContract;
use Modules\Payroll\Entities\PayrollRun;

class ApprovePayrollRunAction
{
    public function __construct(private readonly PayrollApprovalServiceContract $approvals) {}

    public function execute(PayrollRun $run, int $actorId, ?string $comment = null): PayrollRun
    {
        return $this->approvals->approve($run, $actorId, $comment);
    }
}
