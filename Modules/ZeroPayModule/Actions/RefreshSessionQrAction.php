<?php

namespace Modules\ZeroPayModule\Actions;

use Illuminate\Support\Carbon;
use LogicException;
use Modules\ZeroPayModule\Models\ZeroPayQrCode;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\QrCodeService;

class RefreshSessionQrAction
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {}

    /**
     * Deactivate the current QR code for a session and generate a fresh one.
     *
     * Only active sessions (pending or opened) may have their QR refreshed.
     *
     * @throws LogicException if the session is not active
     */
    public function execute(ZeroPaySession $session, string $payId, string $merchantName): ZeroPayQrCode
    {
        if (! $session->isActive()) {
            throw new LogicException(
                "Cannot refresh QR for session '{$session->session_token}': session is not active (status: '{$session->status}')."
            );
        }

        // Deactivate any existing QR codes for this session.
        ZeroPayQrCode::where('session_id', $session->id)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        // Extend the session expiry to 24 hours from now for the refreshed QR.
        $newExpiry = Carbon::now()->addHours(24);
        $session->update(['expires_at' => $newExpiry]);
        $session->refresh();

        return $this->qrCodeService->generateForSession($session, $payId, $merchantName);
    }
}
