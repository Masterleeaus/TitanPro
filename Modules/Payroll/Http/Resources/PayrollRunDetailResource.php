<?php

namespace Modules\Payroll\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayrollRunDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'run_number' => $this->run_number,
            'period_start' => optional($this->period_start)->toDateString(),
            'period_end' => optional($this->period_end)->toDateString(),
            'status' => $this->status,
            'employee_count' => $this->employee_count,
            'gross_total' => (float) $this->gross_total,
            'deduction_total' => (float) $this->deduction_total,
            'net_total' => (float) $this->net_total,
            'submitted_at' => optional($this->submitted_at)->toIso8601String(),
            'approved_at' => optional($this->approved_at)->toIso8601String(),
            'paid_at' => optional($this->paid_at)->toIso8601String(),
            'metadata' => $this->metadata ?: [],
            'approvals' => PayrollRunApprovalResource::collection($this->whenLoaded('approvals')),
        ];
    }
}
