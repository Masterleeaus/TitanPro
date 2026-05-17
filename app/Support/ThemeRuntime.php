<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ThemeRuntime
{
    public const CACHE_KEY = 'theme-manager.active-theme';

    protected const PRESET_CACHE_KEY = 'theme-manager.active-preset';

    protected const ACTIVE_FILE = 'theme-manager/active-preset.json';

    public static function activeThemeSlug(): ?string
    {
        $installed = self::installedThemes();
        $cached = Cache::get(self::CACHE_KEY);

        if (is_string($cached) && in_array($cached, $installed, true)) {
            return $cached;
        }

        $state = self::readActiveState();
        $slug = self::extractThemeSlug($state, $installed);

        if ($slug !== null) {
            Cache::put(self::CACHE_KEY, $slug, now()->addDay());

            return $slug;
        }

        if (self::hasPersistedThemeSelection($state)) {
            self::repairInvalidActiveTheme($state);
        }

        return null;
    }

    public static function setActiveThemeSlug(string $slug): bool
    {
        $slug = trim($slug);

        if ($slug === '' || ! in_array($slug, self::installedThemes(), true)) {
            return false;
        }

        $state = self::readActiveState();
        $state['active_theme'] = $slug;
        unset($state['theme_slug']);

        self::writeActiveState($state);
        Cache::put(self::CACHE_KEY, $slug, now()->addDay());

        return true;
    }

    public static function clearActiveTheme(): void
    {
        $state = self::readActiveState();
        unset($state['active_theme'], $state['theme_slug']);

        if ($state === []) {
            self::deleteActiveStateFile();
        } else {
            self::writeActiveState($state);
        }

        Cache::forget(self::CACHE_KEY);
    }

    public static function activeThemeManifest(): array
    {
        $theme = self::activeTheme() ?? [];
        $slug = self::activeThemeSlug();

        return [
            'slug' => $slug,
            'preset' => self::activePreset(),
            'theme' => $theme,
            'tokens' => self::tokens(),
            'asset_urls' => self::assetUrls(),
            'diagnostics' => self::diagnostics(),
            'css_variables' => self::cssVariablesArray($theme),
            'updated_at' => now()->toIso8601String(),
        ];
    }

    public static function installedThemes(): array
    {
        $basePath = self::themesBasePath();

        if (! File::isDirectory($basePath)) {
            return [];
        }

        $themes = [];

        foreach (File::directories($basePath) as $directory) {
            $slug = basename($directory);

            if (self::themeManifest($slug) !== null || File::exists($directory . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'theme.css')) {
                $themes[] = $slug;
            }
        }

        sort($themes);

        return array_values(array_unique($themes));
    }

    public static function themePath(string $slug): string
    {
        return self::themesBasePath() . DIRECTORY_SEPARATOR . trim($slug, '/');
    }

    public static function themeManifest(string $slug): ?array
    {
        $themeJson = self::themePath($slug) . DIRECTORY_SEPARATOR . 'theme.json';

        if (! File::exists($themeJson)) {
            return null;
        }

        $decoded = json_decode((string) File::get($themeJson), true);

        if (! is_array($decoded)) {
            return null;
        }

        return self::normalizeThemeManifest($decoded, $slug);
    }

    public static function viewOverridePaths(?string $slug = null): array
    {
        $slug ??= self::activeThemeSlug();

        if (! $slug) {
            return [];
        }

        $themePath = self::themePath($slug);
        $viewsPath = $themePath . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';

        return [
            'theme' => $themePath,
            'views' => $viewsPath,
            'vendor' => $viewsPath . DIRECTORY_SEPARATOR . 'vendor',
        ];
    }

    public static function assetUrls(): array
    {
        $assets = [];
        $activeTheme = self::activeThemeSlug();

        if ($activeTheme) {
            $cssPath = self::themePath($activeTheme) . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'theme.css';

            if (File::exists($cssPath)) {
                $version = (string) @filemtime($cssPath);
                $assets['theme_css'] = url('/theme-assets/' . rawurlencode($activeTheme) . '/css/theme.css') . ($version ? '?v=' . $version : '');
            }
        }

        $theme = self::activeTheme() ?? [];

        foreach ([
            'logo' => $theme['logo'] ?? null,
            'logo_path' => $theme['logo_path'] ?? null,
            'favicon' => $theme['favicon'] ?? null,
            'favicon_path' => $theme['favicon_path'] ?? null,
            'background' => $theme['background'] ?? null,
            'background_path' => $theme['background_path'] ?? null,
            'bg_image' => $theme['bg_image'] ?? null,
            'bg_image_path' => $theme['bg_image_path'] ?? null,
            'font' => $theme['font_path'] ?? null,
            'font_path' => $theme['font_path'] ?? null,
        ] as $key => $value) {
            if (! is_string($value) || $value === '') {
                continue;
            }

            $assets[$key] = self::assetUrl($value);
        }

        return $assets;
    }

    public static function assetUrl(?string $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (str_starts_with($path, 'public/')) {
            return asset(substr($path, 7));
        }

        return asset('storage/' . $path);
    }

    public static function diagnostics(?string $slug = null): array
    {
        $installed = self::installedThemes();
        $active = $slug ?: self::activeThemeSlug();
        $themesPath = self::themesBasePath();
        $activeFile = storage_path('app/' . self::ACTIVE_FILE);
        $issues = [];
        $assets = [];

        if (! File::isDirectory($themesPath)) {
            $issues[] = 'Theme base path is missing: ' . $themesPath;
        }

        if ($active !== null && ! in_array($active, $installed, true)) {
            $issues[] = "Active theme [{$active}] is not installed. Falling back to Filament default.";
        }

        if ($active !== null) {
            $cssPath = self::themePath($active) . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'theme.css';
            $manifest = self::themeManifest($active);

            if (! File::exists($cssPath)) {
                $issues[] = "Active theme CSS is missing for [{$active}] at {$cssPath}. Falling back to Filament default.";
            } else {
                $assets[] = $cssPath;
            }

            if (is_array($manifest) && ($manifest['compatible'] ?? true) === false) {
                $issues[] = "Active theme [{$active}] uses unsupported format version [{$manifest['format_version']}].";
            }
        }

        if (! File::exists($activeFile) && is_string(Cache::get(self::CACHE_KEY))) {
            $issues[] = 'Active theme marker file is missing; state currently survives only in cache.';
        }

        $preset = self::activePreset();
        $theme = self::activeTheme();
        $tokenTable = self::hasTokenTable();

        return [
            'ok' => $issues === [],
            'active' => $active,
            'active_preset' => $preset,
            'installed' => $installed,
            'themes_path' => $themesPath,
            'assets' => $assets,
            'issues' => $issues,
            'theme_loaded' => is_array($theme),
            'theme_label' => is_array($theme) ? ($theme['label'] ?? null) : null,
            'token_table_exists' => $tokenTable,
            'token_count' => $tokenTable ? self::safeTokenCount() : 0,
            'active_file_exists' => File::exists($activeFile),
            'active_file' => $activeFile,
            'cache_key' => self::PRESET_CACHE_KEY,
            'asset_urls_count' => count(self::assetUrls()),
        ];
    }

    public static function activePreset(): ?string
    {
        $cached = Cache::get(self::PRESET_CACHE_KEY);

        if (is_string($cached) && class_exists(ThemePalette::class) && ThemePalette::preset($cached)) {
            return $cached;
        }

        $state = self::readActiveState();
        $preset = self::extractPresetSlug($state);

        if ($preset !== null) {
            Cache::put(self::PRESET_CACHE_KEY, $preset, now()->addDay());

            return $preset;
        }

        return null;
    }

    public static function activeTheme(): ?array
    {
        $preset = self::activePreset();

        if ($preset && class_exists(ThemePalette::class) && ThemePalette::preset($preset)) {
            return ThemePalette::preset($preset);
        }

        return self::themeFromTokens();
    }

    public static function tokens(): array
    {
        if (! self::hasTokenTable()) {
            return [];
        }

        return DB::table('titan_theme_tokens')
            ->where('panel', 'global')
            ->orderByRaw("case when panel = 'global' then 0 else 1 end")
            ->orderBy('id')
            ->get(['scope', 'key', 'value'])
            ->map(fn ($row) => [
                'scope' => $row->scope,
                'key' => $row->key,
                'value' => $row->value,
            ])
            ->all();
    }

    public static function themeFromTokens(): ?array
    {
        if (! self::hasTokenTable()) {
            return null;
        }

        $tokens = DB::table('titan_theme_tokens')
            ->where('panel', 'global')
            ->where('scope', 'semantic')
            ->pluck('value', 'key')
            ->all();

        if ($tokens === []) {
            return null;
        }

        return [
            'label' => 'Token Theme',
            'description' => 'Theme loaded from titan_theme_tokens.',
            'primary' => $tokens['--color-primary'] ?? '#38bdf8',
            'primary_2' => $tokens['--color-primary-2'] ?? ($tokens['--color-primary'] ?? '#0ea5e9'),
            'secondary' => $tokens['--color-secondary'] ?? '#2563eb',
            'secondary_2' => $tokens['--color-secondary-2'] ?? ($tokens['--color-secondary'] ?? '#1d4ed8'),
            'accent' => $tokens['--color-accent'] ?? '#22d3ee',
            'accent_2' => $tokens['--color-accent-2'] ?? ($tokens['--color-accent'] ?? '#14b8a6'),
            'dark' => $tokens['--color-dark'] ?? '#020617',
            'dark_2' => $tokens['--color-dark-2'] ?? '#07111f',
            'gray' => $tokens['--color-gray'] ?? '#64748b',
            'gray_2' => $tokens['--color-gray-2'] ?? '#94a3b8',
            'bg' => $tokens['--color-bg'] ?? '#06111d',
            'surface' => $tokens['--color-surface'] ?? '#101b29',
            'surface_2' => $tokens['--color-surface-2'] ?? '#14263a',
            'surface_3' => $tokens['--color-surface-3'] ?? '#1c334d',
            'border' => $tokens['--color-border'] ?? '#24425f',
            'text' => $tokens['--color-text'] ?? '#eff6ff',
            'muted' => $tokens['--color-muted'] ?? '#a9c5df',
            'font' => $tokens['--font-body'] ?? ($tokens['--font-heading'] ?? 'Inter'),
            'radius' => $tokens['--radius'] ?? '14px',
            'bg_image' => $tokens['--bg-image'] ?? null,
        ];
    }

    public static function cssVariablesArray(?array $theme = null): array
    {
        $theme ??= self::activeTheme() ?? [];

        return [
            '--theme-primary' => $theme['primary'] ?? '#38bdf8',
            '--theme-primary-2' => $theme['primary_2'] ?? '#0ea5e9',
            '--theme-secondary' => $theme['secondary'] ?? '#2563eb',
            '--theme-secondary-2' => $theme['secondary_2'] ?? '#1d4ed8',
            '--theme-accent' => $theme['accent'] ?? '#22d3ee',
            '--theme-accent-2' => $theme['accent_2'] ?? '#14b8a6',
            '--theme-dark' => $theme['dark'] ?? '#020617',
            '--theme-dark-2' => $theme['dark_2'] ?? '#07111f',
            '--theme-gray' => $theme['gray'] ?? '#64748b',
            '--theme-gray-2' => $theme['gray_2'] ?? '#94a3b8',
            '--theme-bg' => $theme['bg'] ?? '#06111d',
            '--theme-surface' => $theme['surface'] ?? '#101b29',
            '--theme-surface-2' => $theme['surface_2'] ?? '#14263a',
            '--theme-surface-3' => $theme['surface_3'] ?? '#1c334d',
            '--theme-border' => $theme['border'] ?? '#24425f',
            '--theme-text' => $theme['text'] ?? '#eff6ff',
            '--theme-muted' => $theme['muted'] ?? '#a9c5df',
            '--theme-radius' => $theme['radius'] ?? '14px',
            '--theme-font' => $theme['font'] ?? 'Inter',
        ];
    }

    public static function cssVariables(?array $theme = null): string
    {
        return collect(self::cssVariablesArray($theme))
            ->map(fn ($value, $key) => $key . ': ' . $value . ';')
            ->implode("\n");
    }

    public static function css(): string
    {
        $activeTheme = self::activeThemeSlug();

        if ($activeTheme !== null) {
            $cssPath = self::themePath($activeTheme) . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'theme.css';

            if (File::exists($cssPath)) {
                $version = (string) @filemtime($cssPath);
                $href = url('/theme-assets/' . rawurlencode($activeTheme) . '/css/theme.css') . ($version ? '?v=' . $version : '');

                return '<link rel="stylesheet" id="theme-manager-runtime" href="' . e($href) . '">';
            }

            self::clearActiveTheme();

            return '';
        }

        $theme = self::activeTheme();

        if (! $theme) {
            return '';
        }

        $vars = self::cssVariables($theme);

        return <<<CSS
<style id="theme-manager-admin-runtime">
:root {
{$vars}
    --tm-admin-primary: var(--theme-primary);
    --tm-admin-primary-2: var(--theme-primary-2);
    --tm-admin-secondary: var(--theme-secondary);
    --tm-admin-accent: var(--theme-accent);
    --tm-admin-bg: var(--theme-bg);
    --tm-admin-surface: var(--theme-surface);
    --tm-admin-surface-2: var(--theme-surface-2);
    --tm-admin-surface-3: var(--theme-surface-3);
    --tm-admin-border: var(--theme-border);
    --tm-admin-text: var(--theme-text);
    --tm-admin-muted: var(--theme-muted);
    --tm-admin-radius: var(--theme-radius);
    --tm-admin-font: var(--theme-font);
}
.fi-body,.fi-layout,.fi-panel{font-family:var(--tm-admin-font),ui-sans-serif,system-ui,sans-serif}
.fi-sidebar,.fi-topbar,.fi-main{background-color:var(--tm-admin-bg)!important;color:var(--tm-admin-text)!important}
.fi-sidebar{background:linear-gradient(180deg,var(--tm-admin-surface),var(--tm-admin-bg))!important}
.fi-section,.fi-fo-section,.fi-ta-ctn,.fi-modal-window,.fi-dropdown-panel{background-color:var(--tm-admin-surface)!important;border-color:var(--tm-admin-border)!important;color:var(--tm-admin-text)!important;border-radius:var(--tm-admin-radius)}
.fi-sidebar-item-active>.fi-sidebar-item-button{background:linear-gradient(135deg,color-mix(in srgb,var(--tm-admin-primary) 26%,transparent),color-mix(in srgb,var(--tm-admin-secondary) 18%,transparent))!important;color:var(--tm-admin-text)!important}
.fi-sidebar-item-button:hover{background-color:color-mix(in srgb,var(--tm-admin-primary) 14%,transparent)!important}
.fi-sidebar-item-icon,.fi-topbar .fi-icon-btn,.fi-link,.fi-breadcrumbs-item-label{color:var(--tm-admin-muted)!important}
.fi-sidebar-item-active .fi-sidebar-item-icon,.fi-link:hover{color:var(--tm-admin-accent)!important}
.fi-btn-color-primary,.fi-ac-btn-action{--c-400:var(--tm-admin-primary);--c-500:var(--tm-admin-primary);--c-600:var(--tm-admin-primary-2);background:linear-gradient(135deg,var(--tm-admin-primary),var(--tm-admin-secondary))!important;color:#fff!important}
.fi-input,.fi-select-input,.fi-textarea,.fi-fo-field-wrp,.fi-ta-table{background-color:var(--tm-admin-surface-2)!important;border-color:var(--tm-admin-border)!important;color:var(--tm-admin-text)!important}
.fi-ta-header,.fi-ta-row,.fi-ta-cell,.fi-section-header{border-color:var(--tm-admin-border)!important}
.fi-page h1,.fi-page h2,.fi-page h3,.fi-header-heading{color:var(--tm-admin-text)!important}
.fi-page-subheading,.fi-section-description,.fi-ta-text,.fi-fo-field-wrp-helper-text{color:var(--tm-admin-muted)!important}
</style>
CSS;
    }

    public static function __callStatic(string $name, array $arguments): mixed
    {
        return match ($name) {
            'manifest' => self::activeThemeManifest(),
            'diagnostic', 'debug' => self::diagnostics(),
            'assets' => self::assetUrls(),
            'slug' => self::activeThemeSlug(),
            default => null,
        };
    }

    protected static function themesBasePath(): string
    {
        return (string) config('theme.base_path', base_path('themes'));
    }

    protected static function normalizeThemeManifest(array $manifest, string $fallbackSlug): array
    {
        $formatVersion = (int) ($manifest['format_version'] ?? $manifest['theme_format_version'] ?? 1);
        $stylesheet = $manifest['stylesheet'] ?? $manifest['css'] ?? 'css/theme.css';

        return [
            'slug' => (string) ($manifest['slug'] ?? $fallbackSlug),
            'name' => (string) ($manifest['name'] ?? $manifest['title'] ?? $fallbackSlug),
            'version' => (string) ($manifest['version'] ?? '1.0.0'),
            'format_version' => $formatVersion,
            'engine_format_version' => 1,
            'compatible' => $formatVersion <= 1,
            'stylesheet' => is_string($stylesheet) ? $stylesheet : 'css/theme.css',
        ];
    }

    protected static function readActiveState(): array
    {
        $file = storage_path('app/' . self::ACTIVE_FILE);

        if (! File::exists($file)) {
            return [];
        }

        $raw = trim((string) File::get($file));

        if ($raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        return ['slug' => $raw];
    }

    protected static function writeActiveState(array $state): void
    {
        $dir = storage_path('app/theme-manager');

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $state['updated_at'] = now()->toIso8601String();

        File::put(storage_path('app/' . self::ACTIVE_FILE), json_encode($state, JSON_PRETTY_PRINT));
    }

    protected static function deleteActiveStateFile(): void
    {
        $file = storage_path('app/' . self::ACTIVE_FILE);

        if (File::exists($file)) {
            File::delete($file);
        }
    }

    protected static function extractThemeSlug(array $state, array $installed): ?string
    {
        $themeField = $state['theme'] ?? null;

        $candidates = [
            $state['active_theme'] ?? null,
            $state['theme_slug'] ?? null,
            is_array($themeField) ? ($themeField['slug'] ?? null) : (is_string($themeField) ? $themeField : null),
            $state['slug'] ?? null,
            config('theme.active'),
        ];

        foreach ($candidates as $candidate) {
            if (is_string($candidate) && in_array($candidate, $installed, true)) {
                return $candidate;
            }
        }

        return null;
    }

    protected static function extractPresetSlug(array $state): ?string
    {
        if (! class_exists(ThemePalette::class)) {
            return null;
        }

        $candidates = [
            $state['preset'] ?? null,
            $state['slug'] ?? null,
        ];

        foreach ($candidates as $candidate) {
            if (is_string($candidate) && ThemePalette::preset($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    protected static function hasPersistedThemeSelection(array $state): bool
    {
        return isset($state['active_theme'])
            || isset($state['theme_slug'])
            || (is_string($state['theme'] ?? null))
            || (is_array($state['theme'] ?? null) && isset($state['theme']['slug']))
            || isset($state['slug']);
    }

    protected static function repairInvalidActiveTheme(array $state): void
    {
        unset($state['active_theme'], $state['theme_slug']);

        $theme = $state['theme'] ?? null;

        if (is_string($theme)) {
            unset($state['theme']);
        }

        $slug = $state['slug'] ?? null;

        if (is_string($slug) && (! class_exists(ThemePalette::class) || ! ThemePalette::preset($slug))) {
            unset($state['slug']);
        }

        if ($state === []) {
            self::deleteActiveStateFile();
        } else {
            self::writeActiveState($state);
        }

        Cache::forget(self::CACHE_KEY);
    }

    protected static function hasTokenTable(): bool
    {
        try {
            return Schema::hasTable('titan_theme_tokens');
        } catch (\Throwable) {
            return false;
        }
    }

    protected static function safeTokenCount(): int
    {
        try {
            return self::hasTokenTable() ? (int) DB::table('titan_theme_tokens')->where('panel', 'global')->count() : 0;
        } catch (\Throwable) {
            return 0;
        }
    }
}
