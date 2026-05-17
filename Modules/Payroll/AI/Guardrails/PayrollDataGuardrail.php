<?php

namespace Modules\Payroll\AI\Guardrails;

class PayrollDataGuardrail
{
    public function redact(array $payload): array
    {
        foreach (['tax_file_number', 'ssn', 'bank_account', 'bsb', 'routing_number'] as $key) {
            if (array_key_exists($key, $payload)) { $payload[$key] = '[redacted]'; }
        }
        return $payload;
    }
}
