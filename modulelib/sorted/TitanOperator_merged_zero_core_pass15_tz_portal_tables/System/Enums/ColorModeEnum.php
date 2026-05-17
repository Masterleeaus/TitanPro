<?php

namespace App\Extensions\TitanOperator\System\Enums;

use App\Enums\Traits\EnumTo;

enum ColorModeEnum: string
{
    use EnumTo;

    case solid = 'solid';

    case gradient = 'gradient';

    case none = 'none';
}
