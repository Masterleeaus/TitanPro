<?php

namespace Modules\Biometric\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Biometric\Events\AnomalyDetected;
use Modules\Biometric\Events\OvertimeThresholdReached;

class NotifyManagerOnAnomalyListener
{
    public function handle(AnomalyDetected|OvertimeThresholdReached $event): void
    {
        Log::warning('[Biometric] Manager alert', [
            'event' => $event::class,
            'company_id' => $event->companyId,
            'employee_id' => $event->employeeId,
        ]);
    }
}

