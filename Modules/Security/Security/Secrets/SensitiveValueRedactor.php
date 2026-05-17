<?php

namespace Modules\Security\Security\Secrets;

class SensitiveValueRedactor
{
    private const SENSITIVE_KEYS = [
        'password', 'passwd', 'token', 'secret', 'api_key', 'apikey', 'authorization', 'cookie', 'signature',
    ];

    public function redact(array $payload): array
    {
        foreach ($payload as $key => $value) {
            $normalized = strtolower((string) $key);
            $isSensitive = collect(self::SENSITIVE_KEYS)->contains(fn ($needle) => str_contains($normalized, $needle));

            if ($isSensitive) {
                $payload[$key] = '[redacted]';
                continue;
            }

            if (is_array($value)) {
                $payload[$key] = $this->redact($value);
            }
        }

        return $payload;
    }
}
