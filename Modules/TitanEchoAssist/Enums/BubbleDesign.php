<?php

namespace Modules\TitanEchoAssist\Enums;

enum BubbleDesign: string
{
    case blank = 'blank';
    case plain = 'plain';
    case links = 'links';
    case modern = 'modern';
    case suggestions = 'suggestions';
    case promo_banner = 'promo_banner';

    public static function toArray(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }
}
