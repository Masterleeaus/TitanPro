<?php

namespace App\Services\TitanCalendarSystem\Adapters;

use App\Services\TitanCalendarSystem\Contracts\CalendarSourceAdapterInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class JobCalendarAdapter implements CalendarSourceAdapterInterface
{
    public function sourceKey(): string
    {
        return 'job';
    }

    public function enabledForTeam(?int $teamId = null): bool
    {
        return Schema::hasTable('tz_jobs');
    }

    public function events(array $filters = []): array
    {
        $query = DB::table('tz_jobs')
            ->selectRaw("id, COALESCE(title, CONCAT('Job #', id)) as title_text, scheduled_start_at as event_start, scheduled_end_at as event_end, status as status_text");

        if (! empty($filters['team_id'])) {
            $query->where('team_id', $filters['team_id']);
        }
        if (! empty($filters['start'])) {
            $query->where('scheduled_start_at', '>=', $filters['start']);
        }
        if (! empty($filters['end'])) {
            $query->where('scheduled_start_at', '<=', $filters['end']);
        }

        return $query->orderBy('scheduled_start_at')->limit(500)->get()->map(function ($row): array {
            return [
                'id' => 'job-' . $row->id,
                'title' => mb_strimwidth((string) ($row->title_text ?? ''), 0, 60, '…'),
                'start' => $row->event_start ? Carbon::parse($row->event_start)->toIso8601String() : null,
                'end' => $row->event_end ? Carbon::parse($row->event_end)->toIso8601String() : null,
                'allDay' => false,
                'color' => '#0f766e',
                'source' => 'job',
                'type' => 'job',
                'status' => $row->status_text ?: 'open',
                'url' => route('dashboard.user.calendar.index'),
                'meta' => ['source_table' => 'tz_jobs', 'row_id' => $row->id],
            ];
        })->filter(fn (array $event): bool => ! empty($event['start']))->values()->all();
    }
}
