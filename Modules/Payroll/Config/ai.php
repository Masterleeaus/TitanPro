<?php

return [
    'enabled' => env('PAYROLL_AI_ENABLED', false),
    'tools' => [
        'anomaly_detection' => Modules\Payroll\AI\Tools\PayrollAnomalyTool::class,
    ],
    'guardrails' => [
        'redact_sensitive_fields' => Modules\Payroll\AI\Guardrails\PayrollDataGuardrail::class,
    ],
];
