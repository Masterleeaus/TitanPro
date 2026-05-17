<?php

namespace Modules\Biometric\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OvertimeThresholdReached
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly int $companyId,
        public readonly string $employeeId,
        public readonly float $projectedHours,
        public readonly string $riskLevel,
    ) {}
}

