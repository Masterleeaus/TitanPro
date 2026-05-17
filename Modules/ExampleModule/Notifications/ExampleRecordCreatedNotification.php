<?php

namespace Modules\ExampleModule\Notifications;

class ExampleRecordCreatedNotification
{
    public function via($notifiable): array { return ["database"]; } public function toArray($notifiable): array { return ["message"=>"Example record created."]; }
}
