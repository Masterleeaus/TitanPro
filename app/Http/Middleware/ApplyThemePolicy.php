<?php

namespace App\Http\Middleware;

use App\Support\ThemePolicyManager;
use App\Support\ThemePresetManager;
use App\Support\ThemeRuntime;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ApplyThemePolicy
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = method_exists($request->user(), 'currentTeam')
            ? $request->user()?->currentTeam
            : null;

        $resolved = ThemePolicyManager::resolve($request->user(), $tenant);

        View::share('themePolicy', $resolved);

        if (! empty($resolved['theme']) && class_exists(ThemeRuntime::class)) {
            ThemeRuntime::setActiveThemeSlug($resolved['theme']);
        }

        if (! empty($resolved['preset']) && class_exists(ThemePresetManager::class)) {
            ThemePresetManager::setActivePreset($resolved['preset']);
        }

        return $next($request);
    }
}
