<?php

namespace App\Services\TitanScheduleEngine\Handlers;

use App\Models\Tz\TzScheduledTask;

class CreateFollowUpHandler extends AbstractScheduledTaskHandler
{
    protected function taskType(): string
    {
        return 'create_follow_up';
    }

    public function handle(TzScheduledTask $task): array
    {
        return [
            'status' => 'completed',
            'handler' => static::class,
            'task_type' => 'create_follow_up',
            'payload' => $task->payload_json ?: [],
            'message' => 'CreateFollowUpHandler executed.',
        ];
    }
}
