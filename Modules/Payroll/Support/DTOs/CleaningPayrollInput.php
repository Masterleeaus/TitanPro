<?php

namespace Modules\Payroll\Support\DTOs;

class CleaningPayrollInput
{
    /** @param array<int, CleaningShift|array> $shifts */
    public function __construct(
        public readonly int $companyId,
        public readonly int $userId,
        public readonly array $shifts,
        public readonly bool $isContractor = false,
        public readonly array $extraEarnings = [],
        public readonly array $deductions = [],
        public readonly array $metadata = [],
    ) {}

    /** @return array<int, CleaningShift> */
    public function normalizedShifts(): array
    {
        return array_map(fn ($shift) => $shift instanceof CleaningShift ? $shift : CleaningShift::fromArray($shift), $this->shifts);
    }
}
