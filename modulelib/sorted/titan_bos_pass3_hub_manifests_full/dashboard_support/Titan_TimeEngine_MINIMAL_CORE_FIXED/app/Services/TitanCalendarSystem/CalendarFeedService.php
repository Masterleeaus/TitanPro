<?php

namespace App\Services\TitanCalendarSystem;

class CalendarFeedService
{
    public function __construct(protected CalendarAggregationService $aggregationService)
    {
    }

    public function feed(array $filters = []): array
    {
        return array_values($this->aggregationService->aggregate($filters));
    }
}
