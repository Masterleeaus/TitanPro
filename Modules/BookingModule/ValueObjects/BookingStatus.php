<?php

namespace Modules\BookingModule\ValueObjects;

final class BookingStatus
{
    public const REQUESTED = 'requested';
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';
    public const ONGOING = 'ongoing';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';

    public static function terminal(): array
    {
        return [self::COMPLETED, self::CANCELLED];
    }
}
