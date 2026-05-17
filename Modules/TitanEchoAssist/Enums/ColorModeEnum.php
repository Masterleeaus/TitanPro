<?php

namespace Modules\TitanEchoAssist\Enums;

enum ColorModeEnum: string
{
    case solid = 'solid';
    case gradient = 'gradient';
    case none = 'none';
    public static function toArray(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }
}
