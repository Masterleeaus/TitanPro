<?php

namespace App\Extensions\TitanCommand\System\Enums;

enum StatusEnum: string
{
    case active = 'active';
    case paused = 'paused';
    case disabled = 'disabled';
}
