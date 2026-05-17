<?php

declare(strict_types=1);

namespace Modules\Dispatch\Support\Enums;

enum DispatchStatus: string
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Dispatched = 'dispatched';
    case EnRoute = 'en_route';
    case OnSite = 'on_site';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case NoAccess = 'no_access';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Scheduled => 'Scheduled',
            self::Dispatched => 'Dispatched',
            self::EnRoute => 'En Route',
            self::OnSite => 'On Site',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
            self::NoAccess => 'No Access',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $status): array => [$status->value => $status->label()])->all();
    }
}
