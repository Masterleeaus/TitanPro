<?php

namespace Modules\GroundZeroOps\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\GroundZeroOps\Actions\LogIncidentAction;
use Modules\GroundZeroOps\Http\Requests\LogIncidentRequest;
use Modules\GroundZeroOps\Http\Resources\IncidentResource;

class IncidentController
{
    public function store(LogIncidentRequest $request, LogIncidentAction $action): JsonResponse
    {
        $companyId = (int) ($request->user()?->company_id ?? 0);

        $incident = $action->execute(
            reportedBy: (int) ($request->input('reported_by') ?? $request->user()?->id ?? 0),
            details: (array) $request->input('details', []),
            jobId: $request->filled('job_id') ? (int) $request->integer('job_id') : null,
            severity: (string) $request->input('severity', 'medium'),
            actorId: (int) ($request->user()?->id ?? 0),
            companyId: $companyId,
        );

        return (new IncidentResource($incident))->response()->setStatusCode(201);
    }
}
