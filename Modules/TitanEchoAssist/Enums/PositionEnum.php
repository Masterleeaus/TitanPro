<?php

namespace Modules\TitanEchoAssist\Enums;

enum PositionEnum: string
{
    case left = 'left';
    case right = 'right';

    public static function toArray(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }
}
