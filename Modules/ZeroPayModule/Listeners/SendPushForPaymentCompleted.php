<?php

namespace Modules\ZeroPayModule\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZeroPayModule\Events\PaymentCompleted;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;
use Modules\ZeroPayModule\Services\WebPushService;

class SendPushForPaymentCompleted implements ShouldQueue
{
    public function __construct(private readonly WebPushService $pushService) {}

    public function handle(PaymentCompleted $event): void
    {
        // Resolve the transaction to find the owning user.
        $transaction = ZeroPayTransaction::where('gateway_reference', $event->reference)->first();

        $userId = $transaction?->user_id
            ?? ($event->paymentData['user_id'] ?? null);

        if (! $userId) {
            return;
        }

        $amount = isset($event->paymentData['amount'])
            ? number_format((float) $event->paymentData['amount'], 2)
            : '';
        $currency = $event->paymentData['currency'] ?? 'AUD';
        $body = $amount ? "You received {$currency} {$amount}." : 'Payment received successfully.';

        $this->pushService->notifyUser((int) $userId, [
            'event' => 'payment.completed',
            'title' => '✅ Payment Received',
            'body' => $body,
            'url' => '/transactions',
        ]);
    }
}
