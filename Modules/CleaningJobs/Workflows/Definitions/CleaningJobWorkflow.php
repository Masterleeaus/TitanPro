<?php

namespace Modules\CleaningJobs\Workflows\Definitions;

class CleaningJobWorkflow
{
    public const STATUS_OPEN = 'open';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_ON_HOLD = 'on_hold';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public static function transitions(): array
    {
        return [
            self::STATUS_OPEN => [self::STATUS_SCHEDULED, self::STATUS_CANCELLED, self::STATUS_ON_HOLD],
            self::STATUS_SCHEDULED => [self::STATUS_IN_PROGRESS, self::STATUS_CANCELLED, self::STATUS_ON_HOLD],
            self::STATUS_IN_PROGRESS => [self::STATUS_COMPLETED, self::STATUS_ON_HOLD, self::STATUS_CANCELLED],
            self::STATUS_ON_HOLD => [self::STATUS_OPEN, self::STATUS_SCHEDULED, self::STATUS_CANCELLED],
            self::STATUS_COMPLETED => [],
            self::STATUS_CANCELLED => [],
        ];
    }

    public static function canTransition(?string $from, string $to): bool
    {
        $from = $from ?: self::STATUS_OPEN;
        return $from === $to || in_array($to, self::transitions()[$from] ?? [], true);
    }

    public static function terminalStatuses(): array
    {
        return [self::STATUS_COMPLETED, self::STATUS_CANCELLED, 'done'];
    }
}
