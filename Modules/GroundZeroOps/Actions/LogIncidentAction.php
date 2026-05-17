<?php

namespace Modules\GroundZeroOps\Actions;

use Modules\GroundZeroOps\Events\IncidentLogged;
use Modules\GroundZeroOps\Models\Incident;
use Modules\GroundZeroOps\Support\Scopes\ScopedByCompany;

class LogIncidentAction
{
    /**
     * @param  array<string, mixed>  $details
     */
    public function execute(
        int $reportedBy,
        array $details,
        ?int $jobId = null,
        string $severity = 'medium',
        ?int $actorId = null,
        ?int $companyId = null,
    ): Incident {
        $resolvedCompanyId = $companyId ?? ScopedByCompany::resolveCompanyId();

        if ($resolvedCompanyId === null) {
            abort(403, 'Unable to resolve tenant context.');
        }

        $incident = Incident::query()->create([
            'company_id' => $resolvedCompanyId,
            'job_id' => $jobId,
            'reported_by' => $reportedBy,
            'severity' => $severity,
            'status' => 'open',
            'details' => $details,
            'logged_at' => now(),
        ]);

        event(new IncidentLogged(
            companyId: $resolvedCompanyId,
            actorId: $actorId ?? $reportedBy,
            sourceId: $incident->id,
            payload: [
                'incident_id' => $incident->id,
                'job_id' => $incident->job_id,
                'severity' => $incident->severity,
            ],
        ));

        return $incident;
    }
}
