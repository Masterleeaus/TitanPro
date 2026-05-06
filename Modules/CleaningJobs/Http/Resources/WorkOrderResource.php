<?php

namespace Modules\CleaningJobs\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->wo_id ?? 'CJ-'.$this->id,
            'title' => $this->title ?? $this->wo_detail,
            'description' => $this->description ?? $this->notes,
            'status' => $this->status,
            'priority' => $this->priority,
            'client_id' => $this->client_id ?? $this->client,
            'technician_id' => $this->technician_id ?? $this->assign,
            'scheduled_for' => optional($this->scheduled_for)->toISOString(),
            'due_by' => optional($this->due_by ?? $this->due_date)->toISOString(),
            'location' => $this->location,
            'total_estimate' => $this->total_estimate ?? $this->getWorkorderTotalAmount(),
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
