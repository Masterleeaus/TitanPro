<?php

namespace Modules\Payroll\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayrollRunApprovalResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'step' => $this->step,
            'status' => $this->status,
            'approver_id' => $this->approver_id,
            'comment' => $this->comment,
            'acted_at' => optional($this->acted_at)->toIso8601String(),
        ];
    }
}
