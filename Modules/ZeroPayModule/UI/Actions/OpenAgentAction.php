<?php

namespace Modules\ZeroPayModule\UI\Actions;

final class OpenAgentAction
{
    public static function name(): string
    {
        return str(self::class)->afterLast('\\')->beforeLast('Action')->kebab()->toString();
    }
}
