<?php

namespace App\Services\TitanScheduleEngine\Handlers;

use App\Models\Tz\TzScheduledTask;

class SendReminderHandler extends AbstractScheduledTaskHandler
{
    protected function taskType(): string
    {
        return 'send_reminder';
    }

    public function handle(TzScheduledTask $task): array
    {
        return [
            'status' => 'completed',
            'handler' => static::class,
            'task_type' => 'send_reminder',
            'payload' => $task->payload_json ?: [],
            'message' => 'SendReminderHandler executed.',
        ];
    }
}
