<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Services\TitanCalendarSystem\CalendarService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TitanCalendarController extends Controller
{
    public function __construct(protected CalendarService $calendarService)
    {
    }

    public function index(Request $request)
    {
        $bootStatus = $this->calendarService->bootStatus();

        return view('panel.user.calendar.index', [
            'calendarBootStatus' => $bootStatus,
            'calendarSummary' => $this->buildCalendarSummary($bootStatus),
            'calendarOps' => $this->buildOperationsData(),
            'activeSource' => $request->string('source')->toString(),
            'activeStatus' => $request->string('status')->toString(),
        ]);
    }

    protected function buildCalendarSummary(array $bootStatus): array
    {
        $eventsCount = Schema::hasTable('tz_calendar_events') ? (int) DB::table('tz_calendar_events')->count() : 0;
        $tasksCount = Schema::hasTable('tz_scheduled_tasks') ? (int) DB::table('tz_scheduled_tasks')->count() : 0;
        $sourcesCount = Schema::hasTable('tz_calendar_event_sources') ? (int) DB::table('tz_calendar_event_sources')->count() : 0;
        $readyTables = collect($bootStatus)->filter()->count();
        $trackedTotal = max($eventsCount + $tasksCount, 1);

        return [
            'events_count' => $eventsCount,
            'tasks_count' => $tasksCount,
            'sources_count' => $sourcesCount,
            'ready_tables' => $readyTables,
            'tracked_total' => $trackedTotal,
            'event_share' => $trackedTotal > 0 ? round(($eventsCount / $trackedTotal) * 100, 1) : 0,
            'task_share' => $trackedTotal > 0 ? round(($tasksCount / $trackedTotal) * 100, 1) : 0,
        ];
    }

    protected function buildOperationsData(): array
    {
        $dispatch = $this->buildDispatchData();
        $roster = $this->buildRosterData();
        $tasks = $this->buildTaskData();
        $timeline = $this->buildTimelineData();

        return compact('dispatch', 'roster', 'tasks', 'timeline');
    }

    protected function buildDispatchData(): array
    {
        if (!Schema::hasTable('tz_jobs')) {
            return [
                'scheduled_today' => 0,
                'unassigned' => 0,
                'completed_today' => 0,
                'items' => collect(),
            ];
        }

        $columns = Schema::getColumnListing('tz_jobs');
        $dateColumn = $this->firstExistingColumn($columns, ['scheduled_at', 'start_at', 'service_date', 'starts_at', 'created_at']);
        $titleColumn = $this->firstExistingColumn($columns, ['title', 'name', 'job_title', 'reference']);
        $statusColumn = $this->firstExistingColumn($columns, ['status', 'job_status', 'state']);
        $assigneeColumn = $this->firstExistingColumn($columns, ['user_id', 'assigned_user_id', 'staff_id']);
        $customerColumn = $this->firstExistingColumn($columns, ['customer_name', 'client_name', 'customer_id']);

        $query = DB::table('tz_jobs');
        if ($dateColumn) {
            $query->orderBy($dateColumn);
        } else {
            $query->latest('id');
        }

        $items = $query->limit(10)->get()->map(function ($row) use ($titleColumn, $statusColumn, $dateColumn, $assigneeColumn, $customerColumn) {
            return [
                'id' => $row->id ?? null,
                'title' => $titleColumn ? ($row->{$titleColumn} ?? 'Job') : 'Job',
                'status' => $statusColumn ? ($row->{$statusColumn} ?? 'pending') : 'pending',
                'scheduled_at' => $dateColumn ? ($row->{$dateColumn} ?? null) : null,
                'assignee' => $assigneeColumn ? ($row->{$assigneeColumn} ?? null) : null,
                'customer' => $customerColumn ? ($row->{$customerColumn} ?? null) : null,
            ];
        });

        $scheduledToday = 0;
        $completedToday = 0;
        if ($dateColumn) {
            $scheduledToday = (int) DB::table('tz_jobs')->whereDate($dateColumn, Carbon::today())->count();
            if ($statusColumn) {
                $completedToday = (int) DB::table('tz_jobs')
                    ->whereDate($dateColumn, Carbon::today())
                    ->whereIn($statusColumn, ['completed', 'done', 'closed'])
                    ->count();
            }
        }

        $unassigned = 0;
        if ($assigneeColumn) {
            $unassigned = (int) DB::table('tz_jobs')
                ->where(function ($q) use ($assigneeColumn) {
                    $q->whereNull($assigneeColumn)->orWhere($assigneeColumn, 0);
                })
                ->count();
        }

        return [
            'scheduled_today' => $scheduledToday,
            'unassigned' => $unassigned,
            'completed_today' => $completedToday,
            'items' => $items,
        ];
    }

    protected function buildRosterData(): array
    {
        if (!Schema::hasTable('users')) {
            return [
                'active_staff' => 0,
                'scheduled_staff' => 0,
                'available_staff' => 0,
                'items' => collect(),
            ];
        }

        $columns = Schema::getColumnListing('users');
        $nameColumn = $this->firstExistingColumn($columns, ['name', 'full_name', 'first_name']);
        $emailColumn = $this->firstExistingColumn($columns, ['email']);

        $staff = DB::table('users')->orderByDesc('id')->limit(8)->get()->map(function ($row) use ($nameColumn, $emailColumn) {
            return [
                'id' => $row->id ?? null,
                'name' => $nameColumn ? ($row->{$nameColumn} ?? 'Staff member') : 'Staff member',
                'email' => $emailColumn ? ($row->{$emailColumn} ?? null) : null,
                'assigned_jobs' => 0,
            ];
        });

        if (Schema::hasTable('tz_jobs')) {
            $jobColumns = Schema::getColumnListing('tz_jobs');
            $assigneeColumn = $this->firstExistingColumn($jobColumns, ['user_id', 'assigned_user_id', 'staff_id']);
            if ($assigneeColumn) {
                $jobCounts = DB::table('tz_jobs')
                    ->select($assigneeColumn, DB::raw('count(*) as total'))
                    ->whereNotNull($assigneeColumn)
                    ->groupBy($assigneeColumn)
                    ->pluck('total', $assigneeColumn);

                $staff = $staff->map(function (array $member) use ($jobCounts) {
                    $member['assigned_jobs'] = (int) ($jobCounts[$member['id']] ?? 0);
                    return $member;
                });
            }
        }

        $scheduledStaff = $staff->filter(fn (array $member) => $member['assigned_jobs'] > 0)->count();
        $availableStaff = $staff->filter(fn (array $member) => $member['assigned_jobs'] === 0)->count();

        return [
            'active_staff' => $staff->count(),
            'scheduled_staff' => $scheduledStaff,
            'available_staff' => $availableStaff,
            'items' => $staff,
        ];
    }

    protected function buildTaskData(): array
    {
        if (!Schema::hasTable('tz_scheduled_tasks')) {
            return [
                'pending' => 0,
                'failed' => 0,
                'running' => 0,
                'items' => collect(),
            ];
        }

        $items = DB::table('tz_scheduled_tasks')
            ->orderByDesc('scheduled_for')
            ->limit(8)
            ->get(['id', 'task_type', 'status', 'scheduled_for', 'priority']);

        return [
            'pending' => (int) DB::table('tz_scheduled_tasks')->where('status', 'pending')->count(),
            'failed' => (int) DB::table('tz_scheduled_tasks')->where('status', 'failed')->count(),
            'running' => (int) DB::table('tz_scheduled_tasks')->where('status', 'running')->count(),
            'items' => $items,
        ];
    }

    protected function buildTimelineData(): Collection
    {
        $timeline = collect();

        if (Schema::hasTable('tz_calendar_events')) {
            $eventColumns = Schema::getColumnListing('tz_calendar_events');
            $titleColumn = $this->firstExistingColumn($eventColumns, ['title', 'name']);
            $timeColumn = $this->firstExistingColumn($eventColumns, ['starts_at', 'start_at', 'created_at']);
            $statusColumn = $this->firstExistingColumn($eventColumns, ['status']);
            $sourceColumn = $this->firstExistingColumn($eventColumns, ['source_type', 'event_type']);

            $timeline = $timeline->merge(
                DB::table('tz_calendar_events')->orderByDesc($timeColumn ?? 'id')->limit(6)->get()->map(function ($row) use ($titleColumn, $timeColumn, $statusColumn, $sourceColumn) {
                    return [
                        'kind' => 'event',
                        'title' => $titleColumn ? ($row->{$titleColumn} ?? 'Calendar event') : 'Calendar event',
                        'stamp' => $timeColumn ? ($row->{$timeColumn} ?? null) : null,
                        'meta' => trim(($sourceColumn ? ($row->{$sourceColumn} ?? 'event') : 'event') . ' · ' . ($statusColumn ? ($row->{$statusColumn} ?? 'active') : 'active')),
                    ];
                })
            );
        }

        if (Schema::hasTable('tz_scheduled_tasks')) {
            $timeline = $timeline->merge(
                DB::table('tz_scheduled_tasks')->orderByDesc('scheduled_for')->limit(6)->get(['task_type', 'status', 'scheduled_for'])->map(function ($row) {
                    return [
                        'kind' => 'task',
                        'title' => str($row->task_type ?? 'scheduled_task')->headline()->toString(),
                        'stamp' => $row->scheduled_for ?? null,
                        'meta' => 'task · ' . ($row->status ?? 'pending'),
                    ];
                })
            );
        }

        return $timeline
            ->sortByDesc(fn (array $item) => $item['stamp'] ?? now()->toDateTimeString())
            ->values()
            ->take(10);
    }

    protected function firstExistingColumn(array $columns, array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (in_array($candidate, $columns, true)) {
                return $candidate;
            }
        }

        return null;
    }
}
