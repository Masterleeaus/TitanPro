<?php

namespace Modules\TitanTalk\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Arr;

class InjectTitanTalkMenu
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Only modify responses that behave like normal Laravel responses
        if (!$response || !method_exists($response, 'getContent')) {
            return $response;
        }

        $html = $response->getContent();

        // Only touch HTML, skip JSON/API/etc.
        $contentType = $response->headers->get('Content-Type');
        if (!is_string($html) || ($contentType && stripos($contentType, 'text/html') === false)) {
            return $response;
        }

        // Must have an authenticated user
        $user = auth()->user();
        if (!$user) {
            return $response;
        }

        // Hook for permission checks later
        $entitled = true;
        if (!$entitled) {
            return $response;
        }

        // Resolve Titan Talk URL safely
        $url = null;

        if (Route::has('titantalk.dashboard')) {
            $url = route('titantalk.dashboard');
        } elseif (Route::has('titantalk.index')) {
            $url = route('titantalk.index');
        } elseif (Route::has('titantalk.home')) {
            $url = route('titantalk.home');
        }

        // If no route is registered, bail quietly (no error)
        if (!$url) {
            return $response;
        }

        // Menu label with fallbacks
        $label = __('modules.module.titan-talk');
        if ($label === 'modules.module.titan-talk') {
            $label = __('app.menu.titan-talk');
        }
        if ($label === 'app.menu.titan-talk') {
            $label = 'Titan Talk';
        }

        // The menu item HTML (WorkSuite v5.5+ / Tabler-style friendly)
        // - Works across both legacy sidebar <ul> and newer <ul class="navbar-nav">.
        // - Uses Tabler "ti" icon set (already used elsewhere in WorkSuite).
        $li = '<li class="nav-item titan-talk-menu">'
            . '<a class="nav-link" href="' . e($url) . '">' 
            . '<span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-message-chatbot"></i></span>'
            . '<span class="nav-link-title">' . e($label) . '</span>'
            . '</a></li>';

        // Prevent duplicate injection if multiple middlewares / nested responses occur
        if (stripos($html, 'titan-talk-menu') !== false) {
            return $response;
        }

        // Try to inject into a likely sidebar <ul> by matching common class/id tokens.
        // This is more robust across Worksuite/SmartUI theme variations.
        $injected = false;

        $regexes = [
            // Newer WorkSuite (Tabler) typically uses navbar-nav
            '/(<ul\b[^>]*class="[^"]*(?:navbar-nav)[^"]*"[^>]*>)(.*?)(<\/ul>)/is',
            // Common legacy patterns
            '/(<ul\b[^>]*class="[^"]*(?:sidebar-menu|side-menu|sidebar|menu|nav)[^"]*"[^>]*>)(.*?)(<\/ul>)/is',
            '/(<ul\b[^>]*id="[^"]*(?:sidebarnav|side-menu|sidebar)[^"]*"[^>]*>)(.*?)(<\/ul>)/is',
        ];

        foreach ($regexes as $re) {
            $new = preg_replace($re, '$1$2' . $li . '$3', $html, 1, $count);
            if ($count > 0 && is_string($new)) {
                $html = $new;
                $injected = true;
                break;
            }
        }

        if ($injected) {
            $response->setContent($html);
        }

        return $response;
    }
}
