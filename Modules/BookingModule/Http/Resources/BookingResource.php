<?php

namespace Modules\BookingModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id ?? null,
            'status' => $this->booking_status ?? $this->status ?? null,
            'service_type' => $this->service_type ?? null,
            'service_address' => $this->service_address ?? null,
            'starts_at' => optional($this->starts_at ?? null)->toISOString(),
            'created_at' => optional($this->created_at ?? null)->toISOString(),
        ];
    }
}
