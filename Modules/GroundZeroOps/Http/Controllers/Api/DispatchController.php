<?php

namespace Modules\GroundZeroOps\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\GroundZeroOps\Actions\AssignJobAction;
use Modules\GroundZeroOps\Http\Requests\AssignJobRequest;
use Modules\GroundZeroOps\Http\Resources\DispatchResource;

class DispatchController
{
    public function assign(AssignJobRequest $request, AssignJobAction $action): JsonResponse
    {
        $companyId = (int) ($request->user()?->company_id ?? 0);

        $dispatch = $action->execute(
            jobId: (int) $request->integer('job_id'),
            technicianId: (int) $request->integer('technician_id'),
            actorId: (int) ($request->user()?->id ?? 0),
            companyId: $companyId,
        );

        return (new DispatchResource($dispatch))
            ->response()
            ->setStatusCode(201);
    }
}
