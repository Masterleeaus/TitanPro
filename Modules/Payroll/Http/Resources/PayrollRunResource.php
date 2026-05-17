<?php

namespace Modules\Payroll\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayrollRunResource extends JsonResource
{
    public function toArray($request): array
    {
        return is_array($this->resource) ? $this->resource : parent::toArray($request);
    }
}
