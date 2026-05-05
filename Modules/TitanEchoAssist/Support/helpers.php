<?php

if (! function_exists('titan_chatbot_config')) {
    function titan_chatbot_config(string $key, mixed $default = null): mixed
    {
        return function_exists('config') ? config('titan-chatbot.' . $key, $default) : $default;
    }
}

if (! function_exists('titan_chatbot_path')) {
    function titan_chatbot_path(string $path = ''): string
    {
        $base = dirname(__DIR__);
        return $path === '' ? $base : $base . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
    }
}
