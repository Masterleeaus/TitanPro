<?php

namespace Modules\ZeroPayModule\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZeroPayModule\Events\PaymentStarted;
use Modules\ZeroPayModule\Services\WebPushService;

class SendPushForPaymentStarted implements ShouldQueue
{
    public function __construct(private readonly WebPushService $pushService) {}

    public function handle(PaymentStarted $event): void
    {
        $session = $event->session;

        if (! $session->user_id) {
            return;
        }

        $this->pushService->notifyUser((int) $session->user_id, [
            'event' => 'payment.started',
            'title' => 'Payer Scanning QR',
            'body' => 'Someone is scanning your QR code to make a payment.',
            'url' => '/receive',
        ]);
    }
}
