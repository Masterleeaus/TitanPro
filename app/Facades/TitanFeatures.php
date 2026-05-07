<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void register(string $key, mixed $value, string $module)
 * @method static bool has(string $key)
 * @method static mixed get(string $key, mixed $default = null)
 * @method static array all()
 * @method static ?string owner(string $key)
 * @method static array warnings()
 */
class TitanFeatures extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'titan.features';
    }
}
