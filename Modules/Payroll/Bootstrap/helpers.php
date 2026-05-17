<?php

if (! function_exists('payroll_module_path')) {
    function payroll_module_path(string $path = ''): string
    {
        return __DIR__.'/../'.ltrim($path, '/');
    }
}
