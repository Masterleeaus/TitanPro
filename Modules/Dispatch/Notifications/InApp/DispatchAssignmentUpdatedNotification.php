<?php

declare(strict_types=1);

namespace Modules\Dispatch\Notifications\InApp;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Dispatch\Models\AssignShift;

class DispatchAssignmentUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(public AssignShift $assignment) {}

    /** @return array<int,string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array<string,mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Dispatch assignment updated',
            'assignment_id' => $this->assignment->id,
            'work_order_id' => $this->assignment->work_order_id,
            'appointment_id' => $this->assignment->appointment_id,
            'status' => $this->assignment->status,
        ];
    }
}
