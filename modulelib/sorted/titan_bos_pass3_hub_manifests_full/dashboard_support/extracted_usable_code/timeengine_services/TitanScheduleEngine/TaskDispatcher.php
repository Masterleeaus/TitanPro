<?php

namespace App\Services\TitanScheduleEngine;

use App\Models\Tz\TzScheduledTask;
use App\Services\TitanScheduleEngine\Contracts\ScheduledTaskHandlerInterface;
use App\Services\TitanScheduleEngine\Handlers\CreateFollowUpHandler;
use App\Services\TitanScheduleEngine\Handlers\DispatchWorkHandler;
use App\Services\TitanScheduleEngine\Handlers\InvoiceDueReminderHandler;
use App\Services\TitanScheduleEngine\Handlers\PublishPostHandler;
use App\Services\TitanScheduleEngine\Handlers\SendReminderHandler;
use RuntimeException;

class TaskDispatcher
{
    protected array $handlers;

    public function __construct(?array $handlers = null)
    {
        $this->handlers = $handlers ?: [
            app(PublishPostHandler::class),
            app(SendReminderHandler::class),
            app(CreateFollowUpHandler::class),
            app(InvoiceDueReminderHandler::class),
            app(DispatchWorkHandler::class),
        ];
    }

    public function dispatch(TzScheduledTask $task): array
    {
        foreach ($this->handlers as $handler) {
            if ($handler instanceof ScheduledTaskHandlerInterface && $handler->supports($task)) {
                return $handler->handle($task);
            }
        }

        throw new RuntimeException('No scheduled task handler matched task type: ' . ($task->task_type ?: 'unknown'));
    }
}
