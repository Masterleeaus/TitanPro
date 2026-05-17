<?php

namespace App\Services\TitanScheduleEngine\Handlers;

use App\Models\Tz\TzScheduledTask;

class DispatchWorkHandler extends AbstractScheduledTaskHandler
{
    protected function taskType(): string
    {
        return 'dispatch_work';
    }

    public function handle(TzScheduledTask $task): array
    {
        return [
            'status' => 'completed',
            'handler' => static::class,
            'task_type' => 'dispatch_work',
            'payload' => $task->payload_json ?: [],
            'message' => 'DispatchWorkHandler executed.',
        ];
    }
}
