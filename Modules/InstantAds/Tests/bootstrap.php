<?php

spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'Modules\\' => dirname(__DIR__, 3) . '/Modules/',
        'App\\' => dirname(__DIR__, 3) . '/app/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (! str_starts_with($class, $prefix)) {
            continue;
        }

        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
});
