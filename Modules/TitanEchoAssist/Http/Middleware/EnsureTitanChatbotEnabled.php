<?php

namespace Modules\TitanChatbot\Http\Middleware;

use Closure;

class EnsureTitanChatbotEnabled
{
    public function handle(mixed $request, Closure $next): mixed
    {
        $enabled = function_exists('config') ? (bool) config('titan-chatbot.enabled', true) : true;
        if (! $enabled) {
            if (function_exists('abort')) {
                abort(403, 'TitanChatbot module is disabled.');
            }

            return ['ok' => false, 'error' => 'module_disabled'];
        }

        return $next($request);
    }
}
