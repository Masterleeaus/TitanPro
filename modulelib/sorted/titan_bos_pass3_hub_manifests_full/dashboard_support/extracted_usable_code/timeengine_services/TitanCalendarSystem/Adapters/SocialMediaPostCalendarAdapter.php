<?php

namespace App\Services\TitanCalendarSystem\Adapters;

use App\Services\TitanCalendarSystem\Contracts\CalendarSourceAdapterInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SocialMediaPostCalendarAdapter implements CalendarSourceAdapterInterface
{
    public function sourceKey(): string
    {
        return 'social_post';
    }

    public function enabledForTeam(?int $teamId = null): bool
    {
        return Schema::hasTable('ext_social_media_posts');
    }

    public function events(array $filters = []): array
    {
        $query = DB::table('ext_social_media_posts')
            ->selectRaw("id, COALESCE(content, CONCAT('Post #', id)) as title_text, scheduled_at as event_start, status as status_text");

        if (! empty($filters['team_id'])) {
            $query->where('company_id', $filters['team_id']);
        }
        if (! empty($filters['start'])) {
            $query->where('scheduled_at', '>=', $filters['start']);
        }
        if (! empty($filters['end'])) {
            $query->where('scheduled_at', '<=', $filters['end']);
        }

        return $query->orderBy('scheduled_at')->limit(500)->get()->map(function ($row): array {
            return [
                'id' => 'social-post-' . $row->id,
                'title' => mb_strimwidth((string) ($row->title_text ?? ''), 0, 60, '…'),
                'start' => $row->event_start ? Carbon::parse($row->event_start)->toIso8601String() : null,
                'end' => null,
                'allDay' => false,
                'color' => '#7c3aed',
                'source' => 'social_post',
                'type' => 'social_post',
                'status' => $row->status_text ?: 'pending',
                'url' => route('dashboard.user.calendar.index'),
                'meta' => ['source_table' => 'ext_social_media_posts', 'row_id' => $row->id],
            ];
        })->filter(fn (array $event): bool => ! empty($event['start']))->values()->all();
    }
}
