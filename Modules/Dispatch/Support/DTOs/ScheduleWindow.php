<?php

declare(strict_types=1);

namespace Modules\Dispatch\Support\DTOs;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use InvalidArgumentException;

final readonly class ScheduleWindow
{
    public function __construct(
        public CarbonImmutable $startsAt,
        public CarbonImmutable $endsAt,
    ) {
        if ($this->endsAt->lessThanOrEqualTo($this->startsAt)) {
            throw new InvalidArgumentException('Schedule window end must be after start.');
        }
    }

    public static function fromStrings(string|DateTimeInterface $startsAt, string|DateTimeInterface|null $endsAt = null, float|int|null $estimatedHours = null): self
    {
        $start = CarbonImmutable::parse($startsAt);
        $end = $endsAt
            ? CarbonImmutable::parse($endsAt)
            : $start->addMinutes((int) max(30, round(((float) ($estimatedHours ?: 1)) * 60)));

        return new self($start, $end);
    }

    public function overlaps(self $other): bool
    {
        return $this->startsAt->lessThan($other->endsAt) && $this->endsAt->greaterThan($other->startsAt);
    }

    public function minutes(): int
    {
        return $this->startsAt->diffInMinutes($this->endsAt);
    }

    public function toArray(): array
    {
        return [
            'starts_at' => $this->startsAt->toDateTimeString(),
            'ends_at' => $this->endsAt->toDateTimeString(),
            'minutes' => $this->minutes(),
        ];
    }
}
