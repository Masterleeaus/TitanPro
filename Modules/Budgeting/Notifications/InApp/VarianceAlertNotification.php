<?php

declare(strict_types=1);

namespace Modules\Budgeting\Notifications\InApp;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Budgeting\Models\BudgetVariance;

class VarianceAlertNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly BudgetVariance $variance) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Budget Variance Alert')
            ->line("A {$this->variance->flag} variance of {$this->variance->variance_pct}% has been detected.")
            ->action('View Report', url('/budgeting'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'variance_alert',
            'variance_id' => $this->variance->id,
            'flag' => $this->variance->flag,
            'variance_pct' => $this->variance->variance_pct,
            'variance_amount' => $this->variance->variance_amount,
        ];
    }
}
