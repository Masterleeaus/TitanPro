<?php

namespace App\Events;

use App\Models\AutomationRun;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired when an automation run fails after exhausting all retry attempts.
 */
class AutomationFailed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly AutomationRun $run,
        public readonly string $exception,
    ) {}
}
