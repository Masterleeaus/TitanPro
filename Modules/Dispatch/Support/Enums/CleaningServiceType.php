<?php

declare(strict_types=1);

namespace Modules\Dispatch\Support\Enums;

enum CleaningServiceType: string
{
    case Regular = 'regular_clean';
    case Deep = 'deep_clean';
    case EndOfLease = 'end_of_lease';
    case Commercial = 'commercial_clean';
    case Carpet = 'carpet_clean';
    case Window = 'window_clean';

    public function defaultDurationMinutes(): int
    {
        return match ($this) {
            self::Regular => 120,
            self::Deep => 240,
            self::EndOfLease => 360,
            self::Commercial => 180,
            self::Carpet => 150,
            self::Window => 120,
        };
    }
}
