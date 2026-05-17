<?php

namespace Modules\ZeroPayModule\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZeroPayModule\Events\PaymentFailed;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;
use Modules\ZeroPayModule\Services\WebPushService;

class SendPushForPaymentFailed implements ShouldQueue
{
    public function __construct(private readonly WebPushService $pushService) {}

    public function handle(PaymentFailed $event): void
    {
        $transaction = ZeroPayTransaction::where('gateway_reference', $event->reference)->first();

        $userId = $transaction?->user_id
            ?? ($event->paymentData['user_id'] ?? null);

        if (! $userId) {
            return;
        }

        $reason = $event->reason ?: 'Payment could not be completed. Please try again or contact support if the issue persists.';

        $this->pushService->notifyUser((int) $userId, [
            'event' => 'payment.failed',
            'title' => '❌ Payment Failed',
            'body' => $reason,
            'url' => '/transactions',
        ]);
    }
}
