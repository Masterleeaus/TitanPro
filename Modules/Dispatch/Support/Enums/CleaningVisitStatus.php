<?php

declare(strict_types=1);

namespace Modules\Dispatch\Support\Enums;

enum CleaningVisitStatus: string
{
    case Scheduled = 'scheduled';
    case Assigned = 'assigned';
    case OnTheWay = 'on_the_way';
    case CheckedIn = 'checked_in';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Missed = 'missed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Scheduled => 'Scheduled',
            self::Assigned => 'Assigned',
            self::OnTheWay => 'On the way',
            self::CheckedIn => 'Checked in',
            self::InProgress => 'In progress',
            self::Completed => 'Completed',
            self::Missed => 'Missed',
            self::Cancelled => 'Cancelled',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $status): array => [$status->value => $status->label()])->all();
    }
}
