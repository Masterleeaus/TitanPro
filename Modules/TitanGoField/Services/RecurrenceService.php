<?php

namespace Modules\TitanGoField\Services;

use Carbon\Carbon;

class RecurrenceService
{
    /**
     * Parse a minimal iCal RRULE and return the next occurrence after $after.
     * Supported keys: FREQ (DAILY|WEEKLY|MONTHLY), INTERVAL, BYHOUR, BYMINUTE.
     */
    public function nextOccurrence(string $rrule, Carbon $after): ?Carbon
    {
        $parts = [];
        foreach (explode(';', $rrule) as $part) {
            [$k, $v] = array_pad(explode('=', $part, 2), 2, null);
            if ($k) {
                $parts[strtoupper(trim($k))] = strtoupper(trim((string) $v));
            }
        }

        $freq     = $parts['FREQ'] ?? 'DAILY';
        $interval = max(1, (int) ($parts['INTERVAL'] ?? 1));
        $hour     = isset($parts['BYHOUR']) ? (int) $parts['BYHOUR'] : $after->hour;
        $minute   = isset($parts['BYMINUTE']) ? (int) $parts['BYMINUTE'] : $after->minute;

        $candidate = $after->copy();

        match ($freq) {
            'WEEKLY'  => $candidate->addWeeks($interval),
            'MONTHLY' => $candidate->addMonths($interval),
            default   => $candidate->addDays($interval),
        };

        $candidate->setTime($hour, $minute, 0);

        return $candidate;
    }
}
