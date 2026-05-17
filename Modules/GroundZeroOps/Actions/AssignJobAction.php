<?php

namespace Modules\GroundZeroOps\Actions;

use Modules\GroundZeroOps\Events\JobAssigned;
use Modules\GroundZeroOps\Models\Dispatch;
use Modules\GroundZeroOps\Services\DispatchService;
use Modules\GroundZeroOps\Support\Scopes\ScopedByCompany;

class AssignJobAction
{
    public function __construct(private readonly DispatchService $dispatchService) {}

    public function execute(int $jobId, int $technicianId, ?int $actorId = null, ?int $companyId = null): Dispatch
    {
        $resolvedCompanyId = $companyId ?? ScopedByCompany::resolveCompanyId();

        if ($resolvedCompanyId === null) {
            abort(403, 'Unable to resolve tenant context.');
        }

        $dispatch = $this->dispatchService->assign([
            'company_id' => $resolvedCompanyId,
            'job_id' => $jobId,
            'technician_id' => $technicianId,
            'assigned_by' => $actorId,
        ]);

        event(new JobAssigned(
            companyId: $resolvedCompanyId,
            actorId: $actorId,
            sourceId: $dispatch->id,
            payload: [
                'job_id' => $dispatch->job_id,
                'technician_id' => $dispatch->technician_id,
                'dispatch_id' => $dispatch->id,
            ],
        ));

        return $dispatch;
    }
}
