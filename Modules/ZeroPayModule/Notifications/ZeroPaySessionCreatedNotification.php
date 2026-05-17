<?php

namespace Modules\ZeroPayModule\Notifications;

class ZeroPaySessionCreatedNotification
{
    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return ['message' => 'Example record created.'];
    }
}
