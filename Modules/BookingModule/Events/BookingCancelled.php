<?php

namespace Modules\BookingModule\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

class BookingCancelled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Model $booking,
        public readonly ?int $companyId = null,
        public readonly ?int $actorId = null,
        public readonly ?string $reason = null,
        public readonly array $payload = [],
    ) {}
}
