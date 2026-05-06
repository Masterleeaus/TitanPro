<?php

namespace Modules\BookingModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id ?? null,
            'name' => $this->name ?? null,
            'appointment_type' => $this->appointment_type ?? null,
            'date' => $this->date ?? null,
            'start_time' => $this->start_time ?? null,
            'end_time' => $this->end_time ?? null,
            'is_enabled' => (bool) ($this->is_enabled ?? false),
        ];
    }
}
