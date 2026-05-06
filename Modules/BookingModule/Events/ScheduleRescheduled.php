<?php

namespace Modules\BookingModule\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Entities\Schedule;

class ScheduleRescheduled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Schedule $schedule,
        public readonly array $oldWindow = [],
        public readonly array $newWindow = [],
        public readonly ?int $companyId = null,
        public readonly ?int $actorId = null,
        public readonly array $payload = [],
    ) {}
}
