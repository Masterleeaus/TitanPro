<?php

namespace Modules\Payroll\Support\DTOs;

final class PayrollComplianceReport
{
    public function __construct(
        public readonly bool $passed,
        public readonly array $violations = [],
        public readonly array $warnings = [],
        public readonly array $metadata = [],
    ) {}

    public function toArray(): array
    {
        return [
            'passed' => $this->passed,
            'violations' => $this->violations,
            'warnings' => $this->warnings,
            'metadata' => $this->metadata,
        ];
    }
}
