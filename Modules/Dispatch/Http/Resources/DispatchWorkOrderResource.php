<?php

declare(strict_types=1);

namespace Modules\Dispatch\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DispatchWorkOrderResource extends JsonResource
{
    /** @return array<string,mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'title' => $this->title,
            'status' => $this->status,
            'priority' => $this->priority,
            'customer_id' => $this->customer_id,
            'technician_id' => $this->technician_id,
            'customer_location_id' => $this->customer_location_id,
            'scheduled_for' => optional($this->scheduled_for)->toISOString(),
            'started_at' => optional($this->started_at)->toISOString(),
            'completed_at' => optional($this->completed_at)->toISOString(),
            'metadata' => $this->metadata ?? [],
        ];
    }
}
