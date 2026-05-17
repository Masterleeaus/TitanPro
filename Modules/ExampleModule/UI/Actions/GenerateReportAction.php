<?php

namespace Modules\ExampleModule\UI\Actions;

use Illuminate\Support\Str;

final class GenerateReportAction
{
    public static function name(): string
    {
        return str(static::class)->afterLast('\\')->beforeLast('Action')->kebab()->toString();
    }

}
