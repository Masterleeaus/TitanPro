<?php

namespace Modules\CleaningJobs\Services;

use Modules\CleaningJobs\Models\JobStage;
use Modules\CleaningJobs\Models\JobTask;

class JobKanbanService
{
    public function board(?int $workOrderId = null): array
    {
        return JobStage::query()->where('is_active', true)->orderBy('order')->get()->map(function (JobStage $stage) use ($workOrderId) {
            $tasks = JobTask::query()->where('stage_id', $stage->id)->when($workOrderId, fn ($q) => $q->where('work_order_id', $workOrderId))->orderBy('order')->get();
            return ['stage' => $stage, 'tasks' => $tasks];
        })->all();
    }

    public function move(JobTask $task, int $stageId, int $order = 0): JobTask
    {
        $task->update(['stage_id' => $stageId, 'status' => JobStage::find($stageId)?->slug ?? $task->status, 'order' => $order]);
        return $task->refresh();
    }
}
