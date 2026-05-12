<?php

namespace Modules\BookingModule\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Models\CleaningBooking;

class BookingApprovalDecided
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly CleaningBooking $booking,
        public readonly bool $approved,
        public readonly ?int $companyId = null,
        public readonly ?int $actorId = null,
        public readonly ?string $reason = null,
        public readonly array $payload = [],
    ) {}
}

