<?php

namespace Modules\ZeroPayModule\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZeroPayModule\Events\PaymentPending;
use Modules\ZeroPayModule\Services\WebPushService;

class SendPushForPaymentPending implements ShouldQueue
{
    public function __construct(private readonly WebPushService $pushService) {}

    public function handle(PaymentPending $event): void
    {
        $session = $event->session;

        if (! $session->user_id) {
            return;
        }

        $amount = number_format((float) $session->amount, 2);
        $currency = $session->currency ?? 'AUD';

        $this->pushService->notifyUser((int) $session->user_id, [
            'event' => 'payment.pending',
            'title' => 'Payment Processing',
            'body' => "Payment of {$currency} {$amount} is being processed.",
            'url' => '/transactions',
        ]);
    }
}
