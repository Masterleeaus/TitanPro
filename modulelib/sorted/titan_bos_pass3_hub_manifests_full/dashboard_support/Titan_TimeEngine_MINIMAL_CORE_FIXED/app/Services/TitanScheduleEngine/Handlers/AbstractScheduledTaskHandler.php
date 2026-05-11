<?php

namespace App\Services\TitanScheduleEngine\Handlers;

use App\Models\Tz\TzScheduledTask;
use App\Services\TitanScheduleEngine\Contracts\ScheduledTaskHandlerInterface;

abstract class AbstractScheduledTaskHandler implements ScheduledTaskHandlerInterface
{
    abstract protected function taskType(): string;

    public function supports(TzScheduledTask $task): bool
    {
        return $task->task_type === $this->taskType() || $task->handler === static::class;
    }
}
