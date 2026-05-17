<?php

namespace Modules\ZeroPayModule\Services;

use Illuminate\Support\Carbon;
use Modules\ZeroPayModule\Models\ZeroPayQrCode;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class QrCodeService
{
    public function generateForSession(ZeroPaySession $session, string $payId, string $merchantName): ZeroPayQrCode
    {
        $expiry = $session->expires_at ?? Carbon::now()->addHours(24);

        $payload = [
            'payid' => $payId,
            'merchant_name' => $merchantName,
            'amount' => $session->amount,
            'currency' => $session->currency ?? 'AUD',
            'reference' => $session->session_token,
            'session_token' => $session->session_token,
            'expiry_timestamp' => $expiry->timestamp,
            'fallback_url' => url('/pay/session/'.$session->session_token),
        ];

        return ZeroPayQrCode::create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'pay_id' => $payId,
            'merchant_name' => $merchantName,
            'amount' => $session->amount,
            'currency' => $session->currency ?? 'AUD',
            'reference' => $session->session_token,
            'session_token' => $session->session_token,
            'payload' => $payload,
            'expiry_timestamp' => $expiry,
            'status' => 'active',
        ]);
    }
}
