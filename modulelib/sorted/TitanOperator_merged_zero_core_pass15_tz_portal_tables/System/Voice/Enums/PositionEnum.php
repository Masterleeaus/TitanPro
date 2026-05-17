<?php

namespace App\Extensions\TitanOperator\System\Voice\Enums;

use App\Enums\Traits\EnumTo;

enum PositionEnum: string
{
    use EnumTo;

    case left = 'left';
    case right = 'right';
}
