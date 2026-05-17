<?php

declare(strict_types=1);

namespace Modules\Budgeting\Notifications\InApp;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Budgeting\Models\ReimbursementBatch;

class ReimbursementPaidNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly ReimbursementBatch $batch) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reimbursement Paid')
            ->line("Your reimbursement batch {$this->batch->reference} of {$this->batch->total_amount} has been paid.")
            ->action('View Details', url('/budgeting'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'reimbursement_paid',
            'batch_id' => $this->batch->id,
            'reference' => $this->batch->reference,
            'total_amount' => $this->batch->total_amount,
        ];
    }
}
