<?php

namespace Modules\Payroll\Notifications\Workflow;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Payroll\Entities\PayrollRun;

class PayrollRunApprovalRequested extends Notification
{
    use Queueable;

    public function __construct(private readonly PayrollRun $run) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payroll run requires approval')
            ->line('A payroll run for '.$this->run->period_start->toDateString().' to '.$this->run->period_end->toDateString().' is ready for review.')
            ->line('Net total: '.number_format((float) $this->run->net_total, 2));
    }

    public function toArray($notifiable): array
    {
        return ['payroll_run_id' => $this->run->id, 'status' => $this->run->status];
    }
}
