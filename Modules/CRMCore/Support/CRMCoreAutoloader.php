<?php

namespace Modules\CRMCore\Support;

final class CRMCoreAutoloader
{
    private static bool $registered = false;

    public static function register(): void
    {
        if (self::$registered) {
            return;
        }

        self::$registered = true;

        spl_autoload_register(static function (string $class): void {
            $prefix = 'Modules\\CRMCore\\';

            if (! str_starts_with($class, $prefix)) {
                return;
            }

            $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix))) . '.php';
            $path = base_path('Modules/CRMCore/' . $relative);

            if (is_file($path)) {
                require_once $path;
            }
        });
    }
}
