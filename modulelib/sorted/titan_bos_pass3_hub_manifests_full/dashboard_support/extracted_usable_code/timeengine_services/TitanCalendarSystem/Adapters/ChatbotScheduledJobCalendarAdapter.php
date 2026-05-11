<?php

namespace App\Services\TitanCalendarSystem\Adapters;

use App\Services\TitanCalendarSystem\Contracts\CalendarSourceAdapterInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChatbotScheduledJobCalendarAdapter implements CalendarSourceAdapterInterface
{
    public function sourceKey(): string
    {
        return 'scheduled_job';
    }

    public function enabledForTeam(?int $teamId = null): bool
    {
        return Schema::hasTable('ext_chatbot_scheduled_jobs');
    }

    public function events(array $filters = []): array
    {
        $query = DB::table('ext_chatbot_scheduled_jobs')
            ->selectRaw("id, CONCAT('Scheduled Job #', id) as title_text, scheduled_date as event_start, status as status_text");

        if (! empty($filters['team_id'])) {
            $query->where('company_id', $filters['team_id']);
        }
        if (! empty($filters['start'])) {
            $query->where('scheduled_date', '>=', $filters['start']);
        }
        if (! empty($filters['end'])) {
            $query->where('scheduled_date', '<=', $filters['end']);
        }

        return $query->orderBy('scheduled_date')->limit(500)->get()->map(function ($row): array {
            return [
                'id' => 'scheduled-job-' . $row->id,
                'title' => $row->title_text,
                'start' => $row->event_start ? Carbon::parse($row->event_start)->startOfDay()->toIso8601String() : null,
                'end' => null,
                'allDay' => true,
                'color' => '#dc2626',
                'source' => 'scheduled_job',
                'type' => 'scheduled_job',
                'status' => $row->status_text ?: 'scheduled',
                'url' => route('dashboard.user.calendar.index'),
                'meta' => ['source_table' => 'ext_chatbot_scheduled_jobs', 'row_id' => $row->id],
            ];
        })->filter(fn (array $event): bool => ! empty($event['start']))->values()->all();
    }
}
