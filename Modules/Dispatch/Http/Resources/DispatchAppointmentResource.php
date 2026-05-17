<?php

declare(strict_types=1);

namespace Modules\Dispatch\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DispatchAppointmentResource extends JsonResource
{
    /** @return array<string,mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'work_order_id' => $this->work_order_id,
            'technician_id' => $this->technician_id,
            'shift_id' => $this->shift_id,
            'customer_location_id' => $this->customer_location_id,
            'starts_at' => optional($this->starts_at)->toISOString(),
            'ends_at' => optional($this->ends_at)->toISOString(),
            'status' => $this->status,
            'location' => $this->location,
            'metadata' => $this->metadata ?? [],
        ];
    }
}
