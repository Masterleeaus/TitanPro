<?php

namespace App\Support\TitanOS\Context;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/**
 * Resolve the current OS context from the request, router and auth state.  This
 * helper deliberately omits any module specific data and returns only
 * high‑level information that the Business OS shell can safely consume.
 */
class OsContextResolver
{
    public static function resolve(): OsContext
    {
        $user = Auth::user();
        $currentRoute = Route::current();

        // Determine path segments and basic route info
        $panelPath = request()->path();
        $segments = array_values(array_filter(explode('/', trim($panelPath, '/'))));
        $firstSegment = $segments[0] ?? '';
        $routeName = $currentRoute?->getName() ?? '';
        $routePath = $currentRoute?->uri() ?? '';

        $panelId = (string) (request()->route('panel_id') ?? '');
        $appKey = '';
        $shellMode = 'unknown';

        // Handle OS prefixed routes (/os, /os/apps, /os/workspace/{appKey})
        if ($firstSegment === 'os') {
            // Launcher pages (/os or /os/apps)
            if (! isset($segments[1]) || $segments[1] === 'apps') {
                $shellMode = 'launcher';
                $appKey = '';
                $panelId = '';
            }
            // Workspace page (/os/workspace/{appKey})
            if (($segments[1] ?? '') === 'workspace') {
                $shellMode = 'workspace';
                $workspaceKey = request()->route('appKey') ?? ($segments[2] ?? '');
                $appDef = \App\Support\TitanOS\AppRegistry::get($workspaceKey);
                $appKey = $appDef?->key ?? $workspaceKey;
                $panelId = $appDef?->panel ?? $workspaceKey;
            }
        } else {
            // Non-OS path — attempt to resolve the app via the registry by key,
            // panel id or route.  The first URL segment is used as the
            // lookup key.
            $appDef = \App\Support\TitanOS\AppRegistry::findByPathSegment($firstSegment);
            $appKey = $appDef?->key ?? $firstSegment;
            if (empty($panelId)) {
                $panelId = $appDef?->panel ?? $firstSegment;
            }
            $shellMode = 'panel';
        }

        return new OsContext(
            panel_id: (string) $panelId,
            panel_path: $panelPath,
            route_name: $routeName,
            route_path: $routePath,
            page_title: '',
            user_id: $user?->id ? (string) $user->id : '',
            user_role: $user?->role ?? '',
            company_id: $user?->company_id ? (string) $user->company_id : '',
            app_key: $appKey,
            shell_mode: $shellMode,
        );
    }
}