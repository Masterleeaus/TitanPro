<?php

namespace App\Services\TitanCalendarSystem\Adapters;

use App\Models\Tz\TzCalendarEvent;
use App\Services\TitanCalendarSystem\Contracts\CalendarSourceAdapterInterface;
use App\Services\TitanCalendarSystem\Support\CalendarEventNormalizer;
use Illuminate\Support\Facades\Schema;

class ManualCalendarAdapter implements CalendarSourceAdapterInterface
{
    public function sourceKey(): string
    {
        return 'manual';
    }

    public function enabledForTeam(?int $teamId = null): bool
    {
        return Schema::hasTable('tz_calendar_events');
    }

    public function events(array $filters = []): array
    {
        $query = TzCalendarEvent::query();

        if (! empty($filters['start'])) {
            $query->where('starts_at', '<=', $filters['end'] ?? $filters['start']);
        }
        if (! empty($filters['end'])) {
            $query->where(function ($builder) use ($filters): void {
                $builder->whereNull('ends_at')->where('starts_at', '>=', $filters['start'] ?? $filters['end'])
                    ->orWhere('ends_at', '>=', $filters['start'] ?? $filters['end']);
            });
        }
        if (! empty($filters['event_type'])) {
            $query->where('event_type', $filters['event_type']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('starts_at')->get()->map(function (TzCalendarEvent $event): array {
            return CalendarEventNormalizer::normalize([
                'id' => 'manual-' . $event->id,
                'title' => $event->title,
                'start' => optional($event->starts_at)->toIso8601String(),
                'end' => optional($event->ends_at)->toIso8601String(),
                'allDay' => (bool) $event->all_day,
                'color' => $event->color ?: '#2563eb',
                'source' => 'manual',
                'event_type' => $event->event_type ?: 'event',
                'status' => $event->status ?: 'active',
                'editable' => true,
                'url' => route('dashboard.user.calendar.index'),
                'meta' => array_merge($event->meta_json ?: [], [
                    'calendar_event_id' => $event->id,
                    'location' => $event->location,
                ]),
            ]);
        })->all();
    }
}
