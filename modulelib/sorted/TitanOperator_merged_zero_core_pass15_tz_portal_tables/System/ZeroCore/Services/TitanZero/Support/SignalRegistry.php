<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support;

class SignalRegistry
{
    public static function all(): array
    {
        return config('titan_operator.zero.signal_registry', []);
    }

    public static function get(string $key): array
    {
        return (array) (static::all()[$key] ?? []);
    }

    public static function stageFor(string $key, string $default = 'signal'): string
    {
        return (string) data_get(static::get($key), 'stage', $default);
    }
}
