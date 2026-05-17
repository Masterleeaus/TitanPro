<?php

namespace Modules\ZeroPayModule\UI\Actions;

final class ImportModuleAction
{
    public static function name(): string
    {
        return str(self::class)->afterLast('\\')->beforeLast('Action')->kebab()->toString();
    }
}
