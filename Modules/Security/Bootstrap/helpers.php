<?php

if (! function_exists('security_module_path')) {
    function security_module_path(string $path = ''): string
    {
        return module_path('Security', $path);
    }
}
