<?php

namespace Modules\Payroll\Actions\Reporting;

use Modules\Payroll\Contracts\Services\CleaningPayrollServiceContract;
use Modules\Payroll\Support\DTOs\CleaningPayrollInput;

class GenerateCleanerPayoutSummaryAction
{
    public function __construct(private readonly CleaningPayrollServiceContract $cleaningPayroll) {}

    public function execute(array $payload): array
    {
        $result = $this->cleaningPayroll->calculate(new CleaningPayrollInput(
            companyId: (int) $payload['company_id'],
            userId: (int) $payload['user_id'],
            shifts: (array) $payload['shifts'],
            isContractor: (bool) ($payload['is_contractor'] ?? false),
            extraEarnings: (array) ($payload['extra_earnings'] ?? []),
            deductions: (array) ($payload['deductions'] ?? []),
            metadata: (array) ($payload['metadata'] ?? []),
        ));

        return $result->toArray();
    }
}
