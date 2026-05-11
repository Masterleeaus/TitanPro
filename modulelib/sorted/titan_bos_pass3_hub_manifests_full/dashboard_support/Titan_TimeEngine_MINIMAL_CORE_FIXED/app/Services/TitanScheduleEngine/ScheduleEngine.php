<?php

namespace App\Services\TitanScheduleEngine;

class ScheduleEngine
{
    public function __construct(protected DueTaskResolver $resolver, protected ScheduledTaskService $service)
    {
    }

    public function runDueTasks(int $limit = 100): array
    {
        $results = [];
        foreach ($this->resolver->due($limit) as $task) {
            $results[] = [
                'task_id' => $task->id,
                'result' => $this->service->run($task),
            ];
        }

        return $results;
    }
}
