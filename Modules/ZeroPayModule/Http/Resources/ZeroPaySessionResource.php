<?php

namespace Modules\ZeroPayModule\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ZeroPaySessionResource extends JsonResource
{
    public function toArray($request): array
    {
        return ['id' => $this->id, 'company_id' => $this->company_id, 'name' => $this->name, 'status' => $this->status];
    }
}
