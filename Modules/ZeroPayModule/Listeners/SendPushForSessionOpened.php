<?php

namespace Modules\ZeroPayModule\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZeroPayModule\Events\SessionOpened;
use Modules\ZeroPayModule\Services\WebPushService;

class SendPushForSessionOpened implements ShouldQueue
{
    public function __construct(private readonly WebPushService $pushService) {}

    public function handle(SessionOpened $event): void
    {
        $session = $event->session;

        if (! $session->user_id) {
            return;
        }

        $this->pushService->notifyUser((int) $session->user_id, [
            'event' => 'session.created',
            'title' => 'Payment Session Opened',
            'body' => 'Your payment session is ready. Share your QR to receive payment.',
            'url' => '/receive',
        ]);
    }
}
