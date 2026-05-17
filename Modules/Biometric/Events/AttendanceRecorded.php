<?php

namespace Modules\Biometric\Events;

use DateTimeInterface;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttendanceRecorded
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly int $companyId,
        public readonly string $employeeId,
        public readonly ?int $userId,
        public readonly DateTimeInterface $occurredAt,
        public readonly bool $clockIn,
        public readonly float $workedHours,
        public readonly ?string $deviceSerial,
    ) {}
}

