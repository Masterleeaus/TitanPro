<?php

if (! function_exists('dispatch_module_path')) {
    function dispatch_module_path(string $path = ''): string
    {
        return __DIR__.'/../'.ltrim($path, '/');
    }
}
