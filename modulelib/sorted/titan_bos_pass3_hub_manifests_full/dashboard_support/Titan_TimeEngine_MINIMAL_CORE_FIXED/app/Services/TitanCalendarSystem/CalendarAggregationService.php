<?php

namespace App\Services\TitanCalendarSystem;

use App\Services\TitanCalendarSystem\Adapters\ChatbotScheduledJobCalendarAdapter;
use App\Services\TitanCalendarSystem\Adapters\InvoiceCalendarAdapter;
use App\Services\TitanCalendarSystem\Adapters\JobCalendarAdapter;
use App\Services\TitanCalendarSystem\Adapters\ManualCalendarAdapter;
use App\Services\TitanCalendarSystem\Adapters\ScheduledTaskCalendarAdapter;
use App\Services\TitanCalendarSystem\Adapters\SocialMediaPostCalendarAdapter;
use App\Services\TitanCalendarSystem\Contracts\CalendarSourceAdapterInterface;

class CalendarAggregationService
{
    protected array $adapters;

    public function __construct(protected RecurrenceService $recurrenceService, protected GoogleCalendarService $googleCalendarService, ?array $adapters = null)
    {
        $this->adapters = $adapters ?: [
            app(ManualCalendarAdapter::class),
            app(JobCalendarAdapter::class),
            app(InvoiceCalendarAdapter::class),
            app(SocialMediaPostCalendarAdapter::class),
            app(ChatbotScheduledJobCalendarAdapter::class),
            app(ScheduledTaskCalendarAdapter::class),
        ];
    }

    public function aggregate(array $filters = []): array
    {
        $events = [];

        foreach ($this->adapters as $adapter) {
            if (! $adapter instanceof CalendarSourceAdapterInterface || ! $adapter->enabledForTeam($filters['team_id'] ?? null)) {
                continue;
            }
            $events = [...$events, ...$adapter->events($filters)];
        }

        $events = [...$events, ...$this->googleCalendarService->pullEvents($filters)];
        $events = $this->recurrenceService->expand($events, $filters);

        if (! empty($filters['source'])) {
            $events = array_values(array_filter($events, fn (array $event): bool => ($event['source'] ?? null) === $filters['source']));
        }
        if (! empty($filters['status'])) {
            $events = array_values(array_filter($events, fn (array $event): bool => ($event['status'] ?? null) === $filters['status']));
        }
        if (! empty($filters['event_type'])) {
            $events = array_values(array_filter($events, fn (array $event): bool => ($event['event_type'] ?? data_get($event, 'extendedProps.event_type')) === $filters['event_type']));
        }

        usort($events, static fn (array $a, array $b): int => strcmp((string) ($a['start'] ?? ''), (string) ($b['start'] ?? '')));
        return $events;
    }
}
