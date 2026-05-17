<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectTitanInPageUiSettings
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $response->headers->contains('content-type', 'text/html')) {
            return $response;
        }

        if (! method_exists($response, 'getContent') || ! method_exists($response, 'setContent')) {
            return $response;
        }

        $content = $response->getContent();

        if (! is_string($content) || ! str_contains($content, '</body>')) {
            return $response;
        }

        $css = asset('titan-ui-settings/titan-ui-settings.css');
        $js = asset('titan-ui-settings/titan-ui-settings.js');
        $inject = "\n<link rel=\"stylesheet\" href=\"{$css}?v=" . @filemtime(public_path('titan-ui-settings/titan-ui-settings.css')) . "\">";
        $inject .= "\n<script defer src=\"{$js}?v=" . @filemtime(public_path('titan-ui-settings/titan-ui-settings.js')) . "\"></script>\n";

        if (! str_contains($content, 'titan-ui-settings.js')) {
            $content = str_replace('</body>', $inject . '</body>', $content);
            $response->setContent($content);
        }

        return $response;
    }
}
