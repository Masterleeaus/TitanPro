<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class ThemePolicyManager
{
    public const FILE = 'theme-manager/theme-policy.json';

    public static function policy(): array
    {
        $file = storage_path('app/' . self::FILE);

        if (! File::exists($file)) {
            return [
                'roles' => [],
                'tenants' => [],
                'users' => [],
                'fallback' => [
                    'theme' => null,
                    'preset' => class_exists(ThemePresetManager::class) ? ThemePresetManager::activePresetSlug() : null,
                ],
            ];
        }

        $json = json_decode((string) File::get($file), true);

        return is_array($json) ? array_replace_recursive([
            'roles' => [],
            'tenants' => [],
            'users' => [],
            'fallback' => [],
        ], $json) : [];
    }

    public static function save(array $policy): void
    {
        $dir = storage_path('app/theme-manager');

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put(storage_path('app/' . self::FILE), json_encode($policy, JSON_PRETTY_PRINT));
    }

    public static function setRole(string $role, ?string $theme = null, ?string $preset = null): void
    {
        $policy = self::policy();
        $policy['roles'][$role] = array_filter([
            'theme' => $theme,
            'preset' => $preset,
            'updated_at' => now()->toIso8601String(),
        ]);

        self::save($policy);
    }

    public static function setTenant(string $tenant, ?string $theme = null, ?string $preset = null): void
    {
        $policy = self::policy();
        $policy['tenants'][$tenant] = array_filter([
            'theme' => $theme,
            'preset' => $preset,
            'updated_at' => now()->toIso8601String(),
        ]);

        self::save($policy);
    }

    public static function setUser(string $userId, ?string $theme = null, ?string $preset = null): void
    {
        $policy = self::policy();
        $policy['users'][$userId] = array_filter([
            'theme' => $theme,
            'preset' => $preset,
            'updated_at' => now()->toIso8601String(),
        ]);

        self::save($policy);
    }

    public static function resolve($user = null, $tenant = null): array
    {
        $policy = self::policy();

        $tenantKey = self::tenantKey($tenant);
        if ($tenantKey && isset($policy['tenants'][$tenantKey])) {
            return self::normalize($policy['tenants'][$tenantKey], 'tenant:' . $tenantKey);
        }

        if ($user) {
            $userKey = (string) ($user->id ?? '');
            if ($userKey !== '' && isset($policy['users'][$userKey])) {
                return self::normalize($policy['users'][$userKey], 'user:' . $userKey);
            }

            if (method_exists($user, 'getRoleNames')) {
                foreach ($user->getRoleNames() as $role) {
                    if (isset($policy['roles'][$role])) {
                        return self::normalize($policy['roles'][$role], 'role:' . $role);
                    }
                }
            }
        }

        return self::normalize($policy['fallback'] ?? [], 'fallback');
    }

    public static function diagnostics(): array
    {
        $policy = self::policy();

        return [
            'counts' => [
                'roles' => count($policy['roles'] ?? []),
                'tenants' => count($policy['tenants'] ?? []),
                'users' => count($policy['users'] ?? []),
            ],
            'policy' => $policy,
        ];
    }

    protected static function normalize(array $entry, string $source): array
    {
        return [
            'source' => $source,
            'theme' => $entry['theme'] ?? null,
            'preset' => $entry['preset'] ?? null,
        ];
    }

    protected static function tenantKey($tenant): ?string
    {
        if (! $tenant) {
            return null;
        }

        if (is_string($tenant)) {
            return $tenant;
        }

        return (string) ($tenant->id ?? $tenant->slug ?? $tenant->uuid ?? null);
    }
}
