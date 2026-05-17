<?php

namespace Modules\ZeroPayModule\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZeroPayModule\Events\SessionExpiring;
use Modules\ZeroPayModule\Services\WebPushService;

class SendPushForSessionExpiring implements ShouldQueue
{
    public function __construct(private readonly WebPushService $pushService) {}

    public function handle(SessionExpiring $event): void
    {
        $session = $event->session;

        if (! $session->user_id) {
            return;
        }

        $this->pushService->notifyUser((int) $session->user_id, [
            'event' => 'session.expiring',
            'title' => '⏰ Session Expiring Soon',
            'body' => 'Your payment QR code will expire in 5 minutes.',
            'url' => '/receive',
        ]);
    }
}
