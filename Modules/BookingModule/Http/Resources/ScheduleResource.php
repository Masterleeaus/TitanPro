<?php

namespace Modules\BookingModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id ?? null,
            'appointment_id' => $this->appointment_id ?? null,
            'assigned_to' => $this->assigned_to ?? null,
            'status' => $this->status ?? null,
            'date' => $this->date ?? null,
            'start_time' => $this->start_time ?? null,
            'end_time' => $this->end_time ?? null,
            'starts_at' => optional($this->starts_at ?? null)->toISOString(),
            'location' => $this->location ?? null,
        ];
    }
}
