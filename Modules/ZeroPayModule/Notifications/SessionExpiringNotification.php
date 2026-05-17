<?php

namespace Modules\ZeroPayModule\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class SessionExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ZeroPaySession $session
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'event' => 'session.expiring',
            'session_id' => $this->session->id,
            'message' => 'Your payment QR code will expire soon.',
        ];
    }
}
