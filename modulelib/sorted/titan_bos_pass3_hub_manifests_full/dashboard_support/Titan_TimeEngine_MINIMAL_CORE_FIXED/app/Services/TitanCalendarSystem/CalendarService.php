<?php

namespace App\Services\TitanCalendarSystem;

use App\Models\Tz\TzCalendarEvent;
use App\Models\Tz\TzScheduledTask;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class CalendarService
{
    public function __construct(protected RecurrenceService $recurrenceService)
    {
    }

    public function bootStatus(): array
    {
        return [
            'events_table' => Schema::hasTable('tz_calendar_events'),
            'scheduled_tasks_table' => Schema::hasTable('tz_scheduled_tasks'),
            'sync_accounts_table' => Schema::hasTable('tz_calendar_sync_accounts'),
            'recurrence_rules_table' => Schema::hasTable('tz_calendar_recurrence_rules'),
        ];
    }

    public function createManualEvent(array $data): TzCalendarEvent
    {
        $event = TzCalendarEvent::create(Arr::except($data, [
            'recurrence_frequency', 'recurrence_interval', 'recurrence_days', 'recurrence_until', 'recurrence_count',
        ]));

        $this->recurrenceService->createOrUpdateForEvent($event, [
            'frequency' => $data['recurrence_frequency'] ?? null,
            'interval_value' => $data['recurrence_interval'] ?? 1,
            'days_of_week' => $data['recurrence_days'] ?? [],
            'until_at' => $data['recurrence_until'] ?? null,
            'occurrence_limit' => $data['recurrence_count'] ?? null,
        ]);

        return $event;
    }

    public function moveEvent(array $data): array
    {
        $id = (string) ($data['id'] ?? '');
        if (str_starts_with($id, 'manual-')) {
            $eventId = (int) str_replace('manual-', '', $id);
            $event = TzCalendarEvent::query()->findOrFail($eventId);
            $event->starts_at = $data['start'];
            $event->ends_at = $data['end'] ?? null;
            $event->all_day = (bool) ($data['allDay'] ?? false);
            $event->save();

            return ['success' => true, 'entity' => 'calendar_event', 'id' => $event->id];
        }

        if (str_starts_with($id, 'scheduled-task:')) {
            $taskId = (int) str_replace('scheduled-task:', '', $id);
            $task = TzScheduledTask::query()->findOrFail($taskId);
            $task->scheduled_for = $data['start'];
            $task->next_run_at = $data['start'];
            $task->save();

            return ['success' => true, 'entity' => 'scheduled_task', 'id' => $task->id];
        }

        return ['success' => false, 'message' => 'Unsupported event id'];
    }
}
