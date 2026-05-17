<?php

namespace Modules\Payroll\Services\Security;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Modules\Payroll\Contracts\Services\PayslipAccessLinkServiceContract;
use Modules\Payroll\Support\DTOs\PayslipDocument;

class PayslipAccessLinkService implements PayslipAccessLinkServiceContract
{
    public function temporaryLink(PayslipDocument $document, array $employee = [], array $options = []): ?string
    {
        if (! (bool) config('payroll.features.secure_payslip_links', true)) {
            return $document->payload['download_url'] ?? null;
        }

        $minutes = (int) ($options['expires_in_minutes'] ?? config('payroll.notifications.payslip_link_expiry_minutes', 10080));
        $token = Crypt::encryptString(json_encode([
            'user_id' => $document->userId,
            'salary_slip_id' => $document->payload['salary_slip_id'] ?? null,
            'path' => $document->storagePath,
            'expires_at' => now()->addMinutes($minutes)->toIso8601String(),
        ]));

        return URL::temporarySignedRoute('payroll.payslips.secure-download', now()->addMinutes($minutes), ['token' => $token]);
    }

    public function verify(string $token): array
    {
        $payload = json_decode(Crypt::decryptString($token), true) ?: [];
        abort_if(empty($payload['expires_at']) || now()->greaterThan($payload['expires_at']), 403, 'Payslip link expired.');
        return $payload;
    }
}
