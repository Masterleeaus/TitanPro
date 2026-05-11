<?php

namespace App\Services\TitanScheduleEngine\Handlers;

use App\Models\Tz\TzScheduledTask;

class PublishPostHandler extends AbstractScheduledTaskHandler
{
    protected function taskType(): string
    {
        return 'publish_post';
    }

    public function handle(TzScheduledTask $task): array
    {
        return [
            'status' => 'completed',
            'handler' => static::class,
            'task_type' => 'publish_post',
            'payload' => $task->payload_json ?: [],
            'message' => 'PublishPostHandler executed.',
        ];
    }
}
