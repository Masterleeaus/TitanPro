<?php

namespace Modules\Payroll\Support\DTOs;

class PayrollCalculationResult
{
    public function __construct(
        public readonly int $userId,
        public readonly float $grossPay,
        public readonly float $netPay,
        public readonly float $totalEarnings,
        public readonly float $totalDeductions,
        public readonly float $totalTaxes,
        public readonly float $totalReimbursements,
        public readonly array $lines = [],
        public readonly array $warnings = [],
        public readonly array $metadata = [],
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'gross_pay' => round($this->grossPay, 2),
            'net_pay' => round($this->netPay, 2),
            'total_earnings' => round($this->totalEarnings, 2),
            'total_deductions' => round($this->totalDeductions, 2),
            'total_taxes' => round($this->totalTaxes, 2),
            'total_reimbursements' => round($this->totalReimbursements, 2),
            'lines' => $this->lines,
            'warnings' => $this->warnings,
            'metadata' => $this->metadata,
        ];
    }
}
