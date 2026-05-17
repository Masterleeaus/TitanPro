<?php

namespace App\Services\TitanCalendarSystem\Support;

class CalendarEventNormalizer
{
    public static function normalize(array $event): array
    {
        return [
            'id' => (string) ($event['id'] ?? uniqid('calendar_', true)),
            'title' => (string) ($event['title'] ?? 'Untitled'),
            'start' => (string) ($event['start'] ?? now()->toIso8601String()),
            'end' => isset($event['end']) && $event['end'] ? (string) $event['end'] : null,
            'allDay' => (bool) ($event['allDay'] ?? $event['all_day'] ?? false),
            'color' => $event['color'] ?? null,
            'source' => (string) ($event['source'] ?? 'manual'),
            'status' => (string) ($event['status'] ?? 'active'),
            'event_type' => (string) ($event['event_type'] ?? $event['type'] ?? 'event'),
            'editable' => (bool) ($event['editable'] ?? false),
            'url' => $event['url'] ?? null,
            'extendedProps' => array_merge([
                'source' => (string) ($event['source'] ?? 'manual'),
                'status' => (string) ($event['status'] ?? 'active'),
                'event_type' => (string) ($event['event_type'] ?? $event['type'] ?? 'event'),
            ], $event['extendedProps'] ?? $event['meta'] ?? []),
        ];
    }
}
