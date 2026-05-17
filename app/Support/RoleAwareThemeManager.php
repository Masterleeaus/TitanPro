<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class RoleAwareThemeManager
{
    public const MARKER_FILE = 'theme-manager/role-theme-map.json';

    public static function map(): array
    {
        $file = storage_path('app/' . self::MARKER_FILE);

        if (! File::exists($file)) {
            return [];
        }

        $json = json_decode((string) File::get($file), true);

        return is_array($json) ? $json : [];
    }

    public static function set(string $role, string $theme): bool
    {
        if (! in_array($theme, ThemeRuntime::installedThemes(), true)) {
            return false;
        }

        $map = self::map();
        $map[$role] = $theme;

        $dir = storage_path('app/theme-manager');

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put(storage_path('app/' . self::MARKER_FILE), json_encode($map, JSON_PRETTY_PRINT));

        Cache::forget(ThemeRuntime::CACHE_KEY);

        return true;
    }

    public static function resolveForUser($user): ?string
    {
        if (! $user) {
            return null;
        }

        $map = self::map();

        if (method_exists($user, 'getRoleNames')) {
            foreach ($user->getRoleNames() as $role) {
                if (isset($map[$role]) && in_array($map[$role], ThemeRuntime::installedThemes(), true)) {
                    return $map[$role];
                }
            }
        }

        return null;
    }
}
