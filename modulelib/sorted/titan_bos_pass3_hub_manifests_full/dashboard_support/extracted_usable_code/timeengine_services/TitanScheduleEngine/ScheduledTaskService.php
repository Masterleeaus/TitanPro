<?php

namespace App\Services\TitanScheduleEngine;

use App\Models\Tz\TzScheduledTask;
use App\Models\Tz\TzScheduledTaskLog;
use App\Models\Tz\TzScheduledTaskRun;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ScheduledTaskService
{
    public function __construct(protected TaskDispatcher $dispatcher, protected RetryService $retryService)
    {
    }

    public function create(array $payload): TzScheduledTask
    {
        $payload['status'] = $payload['status'] ?? 'pending';
        $payload['max_retries'] = $payload['max_retries'] ?? 3;
        $payload['next_run_at'] = $payload['next_run_at'] ?? $payload['scheduled_for'] ?? now();

        return TzScheduledTask::create($payload);
    }

    public function run(TzScheduledTask $task): array
    {
        if (! Schema::hasTable('tz_scheduled_tasks')) {
            return ['status' => 'skipped', 'message' => 'tz_scheduled_tasks table is missing.'];
        }

        $task->status = 'running';
        $task->last_run_at = now();
        $task->save();

        $run = null;
        if (Schema::hasTable('tz_scheduled_task_runs')) {
            $run = TzScheduledTaskRun::create([
                'team_id' => $task->team_id,
                'company_id' => $task->company_id,
                'user_id' => $task->user_id,
                'created_by_team_id' => $task->created_by_team_id,
                'scheduled_task_id' => $task->id,
                'status' => 'running',
                'started_at' => now(),
            ]);
        }

        try {
            $result = $this->dispatcher->dispatch($task);
            $task->status = 'completed';
            $task->executed_at = now();
            $task->next_run_at = null;
            $task->result_json = $result;
            $task->save();

            if ($run) {
                $run->status = 'completed';
                $run->finished_at = now();
                $run->result_json = $result;
                $run->runtime_ms = (int) (microtime(true) * 1000);
                $run->save();
            }

            $this->log($task, 'info', 'Scheduled task completed.', $result);
            return $result;
        } catch (Throwable $throwable) {
            $task->status = 'failed';
            $task->failed_at = now();
            $task->retry_count = (int) $task->retry_count + 1;
            $task->next_run_at = $this->retryService->nextRetryAt($task);
            $task->save();

            if ($run) {
                $run->status = 'failed';
                $run->finished_at = now();
                $run->error_message = $throwable->getMessage();
                $run->save();
            }

            $this->log($task, 'error', $throwable->getMessage(), ['exception' => $throwable::class]);
            return ['status' => 'failed', 'message' => $throwable->getMessage()];
        }
    }

    protected function log(TzScheduledTask $task, string $level, string $message, array $context = []): void
    {
        if (! Schema::hasTable('tz_scheduled_task_logs')) {
            return;
        }

        TzScheduledTaskLog::create([
            'team_id' => $task->team_id,
            'company_id' => $task->company_id,
            'user_id' => $task->user_id,
            'created_by_team_id' => $task->created_by_team_id,
            'scheduled_task_id' => $task->id,
            'level' => $level,
            'message' => $message,
            'context_json' => $context,
        ]);
    }
}
