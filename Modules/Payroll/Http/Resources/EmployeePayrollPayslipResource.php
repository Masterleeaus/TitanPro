<?php

namespace Modules\Payroll\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePayrollPayslipResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource['id'] ?? null,
            'period' => trim(($this->resource['month'] ?? '').' '.($this->resource['year'] ?? '')),
            'gross_salary' => $this->resource['gross_salary'] ?? null,
            'net_salary' => $this->resource['net_salary'] ?? null,
            'status' => $this->resource['status'] ?? null,
            'created_at' => $this->resource['created_at'] ?? null,
        ];
    }
}
