<?php

namespace Modules\CRMCore\Actions\Task;

use Modules\CRMCore\Models\Task;
use Modules\CRMCore\Models\TaskStatus;

class CompleteTaskAction
{
    /**
     * Mark a task as completed.
     */
    public function handle(Task $task): Task
    {
        $completedStatusId = TaskStatus::query()
            ->where('is_completed_status', true)
            ->value('id') ?? $task->task_status_id;

        $task->forceFill([
            'task_status_id' => $completedStatusId,
            'completed_at'   => now(),
        ])->save();

        return $task->refresh();
    }
}
