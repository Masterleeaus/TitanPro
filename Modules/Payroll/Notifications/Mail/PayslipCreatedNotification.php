<?php

namespace Modules\Payroll\Notifications\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Payroll\Support\DTOs\PayslipDocument;

class PayslipCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly PayslipDocument $document, public readonly array $company = []) {}

    public function via(object $notifiable): array
    {
        return config('payroll.notifications.payslip_created', ['mail', 'database']);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $company = $this->company['name'] ?? config('app.name');

        return (new MailMessage)
            ->subject('Your payslip is ready')
            ->greeting('Hello '.($notifiable->name ?? ''))
            ->line('Your payslip for '.$this->document->periodFrom.' to '.$this->document->periodTo.' has been created.')
            ->line('Net pay: '.number_format((float) ($this->document->payload['net_pay'] ?? 0), 2))
            ->line('Company: '.$company)
            ->line('Please sign in to view or download the full payslip.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payroll.payslip.created',
            'user_id' => $this->document->userId,
            'period_from' => $this->document->periodFrom,
            'period_to' => $this->document->periodTo,
            'net_pay' => $this->document->payload['net_pay'] ?? null,
            'storage_path' => $this->document->storagePath,
        ];
    }
}
