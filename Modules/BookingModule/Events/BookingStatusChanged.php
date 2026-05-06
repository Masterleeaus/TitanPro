<?php

namespace Modules\BookingModule\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

class BookingStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Model $booking,
        public readonly ?string $fromStatus,
        public readonly string $toStatus,
        public readonly ?int $companyId = null,
        public readonly ?int $actorId = null,
        public readonly array $payload = [],
    ) {}
}
