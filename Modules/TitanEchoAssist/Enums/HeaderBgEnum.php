<?php

namespace Modules\TitanEchoAssist\Enums;

enum HeaderBgEnum: string
{
    case color = 'color';
    case gradient = 'gradient';
    case image = 'image';

    public static function toArray(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }
}
