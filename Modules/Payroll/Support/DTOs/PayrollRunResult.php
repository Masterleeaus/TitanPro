<?php

namespace Modules\Payroll\Support\DTOs;

use Modules\Payroll\Support\Enums\PayrollRunStatus;

class PayrollRunResult
{
    public function __construct(
        public readonly PayrollRunStatus $status,
        public readonly int $companyId,
        public readonly string $periodFrom,
        public readonly string $periodTo,
        public readonly int $processedCount = 0,
        public readonly float $grossTotal = 0.0,
        public readonly float $netTotal = 0.0,
        public readonly array $slipIds = [],
        public readonly array $warnings = [],
        public readonly array $errors = [],
    ) {}

    public function toArray(): array
    {
        return [
            'status' => $this->status->value,
            'company_id' => $this->companyId,
            'period_from' => $this->periodFrom,
            'period_to' => $this->periodTo,
            'processed_count' => $this->processedCount,
            'gross_total' => round($this->grossTotal, 2),
            'net_total' => round($this->netTotal, 2),
            'slip_ids' => $this->slipIds,
            'warnings' => $this->warnings,
            'errors' => $this->errors,
        ];
    }
}
