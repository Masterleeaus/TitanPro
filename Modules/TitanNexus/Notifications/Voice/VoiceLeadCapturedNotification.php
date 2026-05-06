<?php

namespace Modules\TitanNexus\Notifications\Voice;

use Illuminate\Notifications\Notification;

class VoiceLeadCapturedNotification extends Notification
{
    public function __construct(public array $lead) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return $this->lead;
    }
}
