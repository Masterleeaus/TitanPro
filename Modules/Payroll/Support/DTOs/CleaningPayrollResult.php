<?php

namespace Modules\Payroll\Support\DTOs;

class CleaningPayrollResult
{
    public function __construct(
        public readonly int $companyId,
        public readonly int $userId,
        public readonly float $regularPay,
        public readonly float $loadingPay,
        public readonly float $allowances,
        public readonly float $deductions,
        public readonly float $grossPay,
        public readonly float $netPay,
        public readonly float $totalHours,
        public readonly array $lines,
        public readonly array $warnings = [],
        public readonly array $metadata = [],
    ) {}

    public function toArray(): array
    {
        return [
            'company_id' => $this->companyId,
            'user_id' => $this->userId,
            'regular_pay' => round($this->regularPay, 2),
            'loading_pay' => round($this->loadingPay, 2),
            'allowances' => round($this->allowances, 2),
            'deductions' => round($this->deductions, 2),
            'gross_pay' => round($this->grossPay, 2),
            'net_pay' => round($this->netPay, 2),
            'total_hours' => round($this->totalHours, 4),
            'lines' => $this->lines,
            'warnings' => $this->warnings,
            'metadata' => $this->metadata,
        ];
    }
}
