<?php

namespace Modules\GroundZeroOps\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'job_id' => $this->job_id,
            'reported_by' => $this->reported_by,
            'severity' => $this->severity,
            'status' => $this->status,
            'details' => $this->details,
            'logged_at' => optional($this->logged_at)?->toIso8601String(),
        ];
    }
}
