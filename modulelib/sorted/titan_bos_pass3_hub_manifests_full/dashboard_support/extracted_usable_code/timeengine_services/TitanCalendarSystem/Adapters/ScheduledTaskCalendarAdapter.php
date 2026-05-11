<?php

namespace App\Services\TitanCalendarSystem\Adapters;

use App\Models\Tz\TzScheduledTask;
use App\Services\TitanCalendarSystem\Contracts\CalendarSourceAdapterInterface;
use App\Services\TitanCalendarSystem\Support\CalendarEventNormalizer;
use Illuminate\Support\Facades\Schema;

class ScheduledTaskCalendarAdapter implements CalendarSourceAdapterInterface
{
    public function sourceKey(): string
    {
        return 'schedule';
    }

    public function enabledForTeam(?int $teamId): bool
    {
        return (bool) $teamId && Schema::hasTable('tz_scheduled_tasks');
    }

    public function events(array $filters = []): array
    {
        $query = TzScheduledTask::query()->orderBy('scheduled_for');
        if (! empty($filters['event_type'])) {
            $query->where('task_type', $filters['event_type']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['start'])) {
            $query->where('scheduled_for', '>=', $filters['start']);
        }
        if (! empty($filters['end'])) {
            $query->where('scheduled_for', '<=', $filters['end']);
        }

        return $query->limit(250)->get()->map(function (TzScheduledTask $task): array {
            return CalendarEventNormalizer::normalize([
                'id' => 'scheduled-task:' . $task->id,
                'title' => $task->task_type . ' #' . $task->id,
                'start' => optional($task->scheduled_for)->toIso8601String(),
                'end' => optional($task->scheduled_for)->copy()?->addMinutes(30)?->toIso8601String(),
                'source' => 'schedule',
                'status' => $task->status ?: 'pending',
                'event_type' => $task->task_type ?: 'scheduled_task',
                'color' => '#7c3aed',
                'editable' => true,
                'meta' => [
                    'scheduled_task_id' => $task->id,
                    'handler' => $task->handler,
                ],
            ]);
        })->all();
    }
}
