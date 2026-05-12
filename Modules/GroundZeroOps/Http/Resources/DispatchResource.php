<?php

namespace Modules\GroundZeroOps\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DispatchResource extends JsonResource
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
            'technician_id' => $this->technician_id,
            'status' => $this->status,
            'assigned_by' => $this->assigned_by,
            'assigned_at' => optional($this->assigned_at)?->toIso8601String(),
        ];
    }
}
