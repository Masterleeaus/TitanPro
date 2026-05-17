<?php

namespace App\Http\Middleware;


use App\Support\ThemeRuntime;
use App\Models\PlatformSetting;
use App\Models\RoleUIProfile;
use App\Support\ThemeTokenManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    public function handle(Request $request, Closure $next): Response
    {
        $appearance = $request->cookie('appearance');
        $settings = Cache::remember('platform_settings', 300, fn () => PlatformSetting::current());
        $themeTokens = app(ThemeTokenManager::class)->exportPayload($settings);

        $brandName = $this->settingValue($settings, 'brand_name')
            ?? $this->settingValue($settings, 'brandName')
            ?? config('app.name');

        $branding = [
            'app_name' => $brandName,
            'site_name' => $this->settingValue($settings, 'site_name') ?: $brandName,
            'logo_url' => $this->settingValue($settings, 'logo_url'),
            'favicon_url' => $this->settingValue($settings, 'favicon_url'),
            'primary_color' => $this->settingValue($settings, 'primary_color') ?: '#2563eb',
            'secondary_color' => $this->settingValue($settings, 'secondary_color') ?: '#0f172a',
            'accent_color' => $this->settingValue($settings, 'accent_color') ?: '#14b8a6',
            'surface_color' => $this->settingValue($settings, 'surface_color') ?: '#f8fafc',
            'font_heading' => $this->settingValue($settings, 'font_heading') ?: 'Figtree',
            'font_body' => $this->settingValue($settings, 'font_body') ?: 'Figtree',
            'font_source_url' => $this->settingValue($settings, 'font_source_url'),
            'font_path' => $this->settingValue($settings, 'font_path'),
            'bg_image_path' => $this->settingValue($settings, 'bg_image_path'),
            'support_email' => $this->settingValue($settings, 'support_email'),
            'footer_text' => $this->settingValue($settings, 'footer_text'),
            'meta_title' => $this->settingValue($settings, 'meta_title') ?: $brandName,
            'meta_description' => $this->settingValue($settings, 'meta_description'),
            'custom_css' => $this->settingValue($settings, 'custom_css'),
            'theme_tokens_css' => $themeTokens['css'],
            'theme_color' => $themeTokens['resolved']['--color-primary'] ?? '#2563eb',
        ];

        // Apply role-specific theme overrides when a RoleUIProfile exists.
        $user = $request->user();
        if ($user && $user->organization_id) {
            $role = RoleUIProfile::resolvePrimaryRole($user);
            if ($role) {
                $profile = Cache::remember(
                    "role_ui_profile.{$user->organization_id}.{$role}",
                    300,
                    fn () => RoleUIProfile::forRole($role, $user->organization_id)
                );
                if ($profile) {
                    foreach ($profile->themeOverrides() as $key => $value) {
                        $branding[$key] = $value;
                    }
                }
            }
        }

        View::share('appearance', in_array($appearance, ['light', 'dark', 'system']) ? $appearance : 'system');
        View::share('platformBranding', $branding);

        
        View::share('activeThemeSlug', ThemeRuntime::activeThemeSlug());
        View::share('activeThemeManifest', ThemeRuntime::activeThemeManifest());
        View::share('activeThemeAssets', ThemeRuntime::assetUrls());
        View::share('themeDiagnostics', ThemeRuntime::diagnostics());

        return $next($request);
    }

    private function settingValue(mixed $settings, string $key): mixed
    {
        if (is_array($settings)) {
            return $settings[$key] ?? null;
        }

        if (is_object($settings)) {
            $method = match ($key) {
                'brand_name', 'brandName' => 'brandName',
                'logo_url' => 'logoUrl',
                'favicon_url' => 'faviconUrl',
                default => null,
            };

            if ($method && method_exists($settings, $method)) {
                return $settings->{$method}();
            }

            return $settings->{$key} ?? null;
        }

        return null;
    }
}
