<?php

namespace Modules\Payroll\Contracts\Services;

use Modules\Payroll\Entities\PayrollRun;

interface PayrollApprovalServiceContract
{
    public function submit(PayrollRun $run, ?int $actorId = null): PayrollRun;

    public function approve(PayrollRun $run, int $actorId, ?string $comment = null): PayrollRun;

    public function reject(PayrollRun $run, int $actorId, string $comment): PayrollRun;
}
