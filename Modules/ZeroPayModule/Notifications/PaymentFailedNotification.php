<?php

namespace Modules\ZeroPayModule\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $reference,
        public readonly string $reason = '',
        public readonly array $paymentData = []
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $reason = $this->reason ?: 'Payment could not be completed.';

        return (new MailMessage)
            ->subject('❌ Payment Failed — ZeroPay')
            ->greeting('Hello!')
            ->line('Unfortunately, your payment could not be completed.')
            ->line("Transaction Reference: {$this->reference}")
            ->line("Reason: {$reason}")
            ->action('View Transactions', url('/transactions'))
            ->line('Please try again or contact support if the issue persists.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'event' => 'payment.failed',
            'reference' => $this->reference,
            'reason' => $this->reason,
            'message' => 'Payment failed. '.($this->reason ?: 'Please try again.'),
        ];
    }
}
