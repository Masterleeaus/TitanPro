<?php

namespace Modules\ZeroPayModule\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $reference,
        public readonly string $amount,
        public readonly string $currency,
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
        return (new MailMessage)
            ->subject('✅ Payment Received — ZeroPay')
            ->greeting('Hello!')
            ->line("Payment of {$this->currency} {$this->amount} has been received successfully.")
            ->line("Transaction Reference: {$this->reference}")
            ->action('View Transactions', url('/transactions'))
            ->line('Thank you for using ZeroPay.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'event' => 'payment.completed',
            'reference' => $this->reference,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'message' => "Payment of {$this->currency} {$this->amount} received.",
        ];
    }
}
