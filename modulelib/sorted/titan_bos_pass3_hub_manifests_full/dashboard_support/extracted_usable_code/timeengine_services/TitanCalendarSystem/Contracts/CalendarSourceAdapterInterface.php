<?php

namespace App\Services\TitanCalendarSystem\Contracts;

interface CalendarSourceAdapterInterface
{
    public function sourceKey(): string;

    public function enabledForTeam(?int $teamId = null): bool;

    public function events(array $filters = []): array;
}
