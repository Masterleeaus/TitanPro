<?php

namespace Modules\Payroll\Support\DTOs;

final class PayrollPeriodLock
{
    public function __construct(
        public readonly int $companyId,
        public readonly string $periodFrom,
        public readonly string $periodTo,
        public readonly string $status,
        public readonly array $metadata = [],
    ) {}

    public function toArray(): array
    {
        return [
            'company_id' => $this->companyId,
            'period_from' => $this->periodFrom,
            'period_to' => $this->periodTo,
            'status' => $this->status,
            'metadata' => $this->metadata,
        ];
    }
}
