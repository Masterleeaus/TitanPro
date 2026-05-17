<?php

declare(strict_types=1);

namespace Modules\Dispatch\Support\DTOs;

final readonly class CleaningBoardCard
{
    public function __construct(
        public int $appointmentId,
        public ?int $workOrderId,
        public ?int $cleanerId,
        public ?string $cleanerName,
        public string $title,
        public string $status,
        public ?string $startsAt,
        public ?string $endsAt,
        public ?string $location,
        public bool $isLate,
        public array $metadata = [],
    ) {}

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
