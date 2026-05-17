<?php

namespace Modules\Payroll\Services\Workflow;

use Illuminate\Support\Facades\DB;
use Modules\Payroll\Contracts\Services\PayrollApprovalServiceContract;
use Modules\Payroll\Entities\PayrollRun;
use Modules\Payroll\Entities\PayrollRunApproval;
use Modules\Payroll\Services\Audit\PayrollAuditService;

class PayrollApprovalService implements PayrollApprovalServiceContract
{
    public function __construct(private readonly PayrollAuditService $audit) {}

    public function submit(PayrollRun $run, ?int $actorId = null): PayrollRun
    {
        return DB::transaction(function () use ($run, $actorId) {
            $before = $run->toArray();
            $run->forceFill(['status' => 'pending_approval', 'submitted_at' => now()])->save();
            PayrollRunApproval::query()->firstOrCreate([
                'payroll_run_id' => $run->id,
                'step' => 'finance_review',
            ], ['status' => 'pending']);
            $this->audit->record($run->company_id, 'payroll.run.submitted', $run, $actorId, $before, $run->refresh()->toArray());

            return $run;
        });
    }

    public function approve(PayrollRun $run, int $actorId, ?string $comment = null): PayrollRun
    {
        return DB::transaction(function () use ($run, $actorId, $comment) {
            $before = $run->toArray();
            PayrollRunApproval::query()->updateOrCreate([
                'payroll_run_id' => $run->id,
                'step' => 'finance_review',
            ], [
                'approver_id' => $actorId,
                'status' => 'approved',
                'comment' => $comment,
                'acted_at' => now(),
            ]);
            $run->forceFill(['status' => 'approved', 'approved_at' => now()])->save();
            $this->audit->record($run->company_id, 'payroll.run.approved', $run, $actorId, $before, $run->refresh()->toArray());

            return $run;
        });
    }

    public function reject(PayrollRun $run, int $actorId, string $comment): PayrollRun
    {
        return DB::transaction(function () use ($run, $actorId, $comment) {
            $before = $run->toArray();
            PayrollRunApproval::query()->updateOrCreate([
                'payroll_run_id' => $run->id,
                'step' => 'finance_review',
            ], [
                'approver_id' => $actorId,
                'status' => 'rejected',
                'comment' => $comment,
                'acted_at' => now(),
            ]);
            $run->forceFill(['status' => 'rejected'])->save();
            $this->audit->record($run->company_id, 'payroll.run.rejected', $run, $actorId, $before, $run->refresh()->toArray());

            return $run;
        });
    }
}
