<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support;

class ZeroConfig
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return config('titan_operator.zero.' . $key, $default);
    }

    public static function routePrefix(): string
    {
        return (string) static::get('route.prefix', 'dashboard/user/titanzero');
    }

    public static function routeName(): string
    {
        return (string) static::get('route.name', 'dashboard.user.titanzero.');
    }

    public static function routeMiddleware(): array
    {
        return (array) static::get('route.middleware', ['web', 'auth', 'updateUserActivity']);
    }

    public static function apiPrefix(): string
    {
        return (string) static::get('api.prefix', 'dashboard/user/titanzero/api');
    }

    public static function apiName(): string
    {
        return (string) static::get('api.name', 'dashboard.user.titanzero.api.');
    }

    public static function apiMiddleware(): array
    {
        return (array) static::get('api.middleware', ['web', 'auth', 'updateUserActivity']);
    }
}
