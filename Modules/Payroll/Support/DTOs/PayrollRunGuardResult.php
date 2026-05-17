<?php

namespace Modules\Payroll\Support\DTOs;

class PayrollRunGuardResult
{
    public function __construct(
        public readonly bool $allowed,
        public readonly array $errors = [],
        public readonly array $warnings = [],
        public readonly array $metadata = [],
    ) {}

    public function toArray(): array
    {
        return [
            'allowed' => $this->allowed,
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'metadata' => $this->metadata,
        ];
    }
}
