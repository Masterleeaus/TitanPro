<?php

namespace Modules\Payroll\Services\Security;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Modules\Payroll\Contracts\Services\EmployeePayslipAccessTokenServiceContract;

class EmployeePayslipAccessTokenService implements EmployeePayslipAccessTokenServiceContract
{
    public function issue(int $employeeId, int|string $payslipId, array $claims = []): array
    {
        $expiresAt = now()->addMinutes((int) config('payroll.features.ess_token_ttl_minutes', 60));
        $payload = [
            'jti' => (string) Str::uuid(),
            'employee_id' => $employeeId,
            'payslip_id' => (string) $payslipId,
            'claims' => $claims,
            'expires_at' => $expiresAt->toIso8601String(),
        ];

        return ['token' => Crypt::encryptString(json_encode($payload)), 'expires_at' => $payload['expires_at']];
    }

    public function validate(string $token, int|string $payslipId): array
    {
        try {
            $payload = json_decode(Crypt::decryptString($token), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return ['valid' => false, 'reason' => 'Invalid token.'];
        }

        if (($payload['payslip_id'] ?? null) !== (string) $payslipId) {
            return ['valid' => false, 'reason' => 'Token does not match payslip.'];
        }
        if (now()->greaterThan($payload['expires_at'] ?? now()->subMinute())) {
            return ['valid' => false, 'reason' => 'Token expired.'];
        }

        return ['valid' => true, 'payload' => $payload];
    }
}
