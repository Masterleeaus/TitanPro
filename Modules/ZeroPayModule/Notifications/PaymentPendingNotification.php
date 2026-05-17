<?php

namespace Modules\ZeroPayModule\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class PaymentPendingNotification extends Notification implements ShouldQueue
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
        $amount = number_format((float) $this->session->amount, 2);
        $currency = $this->session->currency ?? 'AUD';

        return [
            'event' => 'payment.pending',
            'session_id' => $this->session->id,
            'amount' => $amount,
            'currency' => $currency,
            'message' => "Payment of {$currency} {$amount} is being processed.",
        ];
    }
}
