<?php

namespace Modules\Payroll\Support\DTOs;

use Carbon\CarbonInterface;

class PayrollCalculationInput
{
    public function __construct(
        public readonly int $companyId,
        public readonly int $userId,
        public readonly CarbonInterface $from,
        public readonly CarbonInterface $to,
        public readonly float $baseSalary = 0.0,
        public readonly float $hourlyRate = 0.0,
        public readonly float $regularHours = 0.0,
        public readonly float $overtimeHours = 0.0,
        public readonly array $earnings = [],
        public readonly array $deductions = [],
        public readonly array $reimbursements = [],
        public readonly array $taxes = [],
        public readonly array $metadata = [],
    ) {}
}
