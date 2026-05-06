<?php

namespace Modules\TitanNexus\Notifications;

class LeadRecordCreatedNotification
{
    public function via($notifiable): array { return ["database"]; } public function toArray($notifiable): array { return ["message"=>"Example record created."]; }
}
