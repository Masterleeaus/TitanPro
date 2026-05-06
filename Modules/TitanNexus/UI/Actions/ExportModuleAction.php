<?php

namespace Modules\TitanNexus\UI\Actions;

use Illuminate\Support\Str;

final class ExportModuleAction
{
    public static function name(): string
    {
        return str(static::class)->afterLast('\\')->beforeLast('Action')->kebab()->toString();
    }

}
