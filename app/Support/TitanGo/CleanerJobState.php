<?php

namespace App\Support\TitanGo;

use Modules\TitanGoField\Models\FieldJob;

class CleanerJobState
{
    public static function nextAction(FieldJob $job): string
    {
        return match ($job->status) {
            FieldJob::STATUS_PENDING, FieldJob::STATUS_APPROVED, FieldJob::STATUS_SCHEDULED => 'Check in',
            FieldJob::STATUS_IN_PROGRESS => 'Continue job',
            FieldJob::STATUS_ON_HOLD => 'Resume job',
            FieldJob::STATUS_COMPLETED => 'View proof',
            default => 'Open job',
        };
    }

    public static function mobileTone(FieldJob $job): string
    {
        if ($job->scheduled_start?->isPast() && ! $job->isCompleted()) {
            return 'late';
        }
        if ($job->scheduled_start?->between(now(), now()->addHour())) {
            return 'next';
        }
        return 'normal';
    }
}
