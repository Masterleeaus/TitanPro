<?php

declare(strict_types=1);

namespace Modules\Budgeting\Notifications\InApp;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Budgeting\Models\Expense;

class ExpenseApprovalNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Expense $expense) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Expense Awaiting Approval')
            ->line("An expense of {$this->expense->currency} {$this->expense->amount} requires your approval.")
            ->line("Description: {$this->expense->description}")
            ->action('Review Expense', url('/budgeting'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'expense_approval',
            'expense_id' => $this->expense->id,
            'amount' => $this->expense->amount,
            'currency' => $this->expense->currency,
            'description' => $this->expense->description,
        ];
    }
}
