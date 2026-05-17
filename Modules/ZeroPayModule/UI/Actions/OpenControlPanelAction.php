<?php

namespace Modules\ZeroPayModule\UI\Actions;

final class OpenControlPanelAction
{
    public static function name(): string
    {
        return str(self::class)->afterLast('\\')->beforeLast('Action')->kebab()->toString();
    }
}
