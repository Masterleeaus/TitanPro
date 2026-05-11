<?php

namespace App\Services\TitanScheduleEngine\Handlers;

use App\Models\Tz\TzScheduledTask;

class InvoiceDueReminderHandler extends AbstractScheduledTaskHandler
{
    protected function taskType(): string
    {
        return 'invoice_due_reminder';
    }

    public function handle(TzScheduledTask $task): array
    {
        return [
            'status' => 'completed',
            'handler' => static::class,
            'task_type' => 'invoice_due_reminder',
            'payload' => $task->payload_json ?: [],
            'message' => 'InvoiceDueReminderHandler executed.',
        ];
    }
}
