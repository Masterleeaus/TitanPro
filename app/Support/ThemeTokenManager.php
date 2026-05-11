<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\PlatformSetting;
use App\Models\TitanThemeToken;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ThemeTokenManager
{
    private const HEX_COLOR_REGEX = '/^#[0-9a-fA-F]{3}(?:[0-9a-fA-F]{3})?$/';
    private const FONT_REGEX = '/^[A-Za-z0-9\s,_-]+$/';

    public function ensureDefaultsSeeded(): void
    {
        if (! $this->canUseLaravel() || ! Schema::hasTable('titan_theme_tokens')) {
            return;
        }

        $count = TitanThemeToken::query()->where('panel', 'global')->count();

        if ($count > 0) {
            return;
        }

        $timestamp = now();
        $rows = array_map(static fn (array $row): array => [
            ...$row,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ], ThemeTokenRegistry::defaultRows());

        TitanThemeToken::query()->insert($rows);
    }

    public function load(?PlatformSetting $settings = null, ?string $panel = null): array
    {
        $tokens = ThemeTokenRegistry::defaults();
        $normalizedPanel = $this->normalizePanel($panel);

        if ($settings) {
            $tokens = $this->applyLegacySettings($tokens, $settings);
        }

        if (! $this->canUseLaravel() || ! Schema::hasTable('titan_theme_tokens')) {
            return $tokens;
        }

        $this->ensureDefaultsSeeded();

        if ($settings) {
            $this->syncLegacySettings($settings);
        }

        $rows = TitanThemeToken::query()
            ->whereIn('panel', $normalizedPanel === 'global' ? ['global'] : ['global', $normalizedPanel])
            ->orderByRaw("case when panel = 'global' then 0 else 1 end")
            ->orderBy('id')
            ->get(['scope', 'key', 'value']);

        foreach ($rows as $row) {
            $tokens[$row->scope][$row->key] = $this->sanitizeStoredToken($row->scope, $row->key, $row->value);
        }

        return $tokens;
    }

    public function semanticEditorState(?PlatformSetting $settings = null, ?string $panel = null): array
    {
        $tokens = $this->load($settings, $panel);
        $resolved = $this->resolvedValues($tokens);
        $defaults = [
            'primary_color' => '#2563eb',
            'secondary_color' => '#0f172a',
            'accent_color' => '#14b8a6',
            'surface_color' => '#f8fafc',
            'font_heading' => 'Figtree',
            'font_body' => 'Figtree',
        ];

        $state = [];

        foreach (ThemeTokenRegistry::editorFields() as $field => $definition) {
            $value = $resolved[$definition['key']] ?? null;
            $state[$field] = match ($definition['type']) {
                'color' => $this->sanitizeColor($value, $defaults[$field]),
                'font' => $this->sanitizeFont($value, $defaults[$field]),
                default => $value ?? $defaults[$field],
            };
        }

        return $state;
    }

    public function savePlatformThemeTokens(PlatformSetting $settings, array $state, ?string $panel = null): void
    {
        if (Schema::hasTable('titan_theme_tokens')) {
            $this->ensureDefaultsSeeded();
            $normalizedPanel = $this->normalizePanel($panel);

            $timestamp = now();
            $rows = [];

            foreach (ThemeTokenRegistry::editorFields() as $field => $definition) {
                $rows[] = [
                    'panel' => $normalizedPanel,
                    'scope' => 'semantic',
                    'key' => $definition['key'],
                    'value' => $this->sanitizeEditorValue($definition['type'], $state[$field] ?? null, ThemeTokenRegistry::defaults()['semantic'][$definition['key']]),
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            $rows[] = [
                'panel' => $normalizedPanel,
                'scope' => 'semantic',
                'key' => '--bg-image',
                'value' => $this->backgroundImageTokenValue($settings->bg_image_path),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];

            TitanThemeToken::query()->upsert($rows, ['panel', 'scope', 'key'], ['value', 'updated_at']);
        }

        $settings->fill([
            'primary_color' => $this->sanitizeColor($state['primary_color'] ?? null, '#2563eb'),
            'secondary_color' => $this->sanitizeColor($state['secondary_color'] ?? null, '#0f172a'),
            'accent_color' => $this->sanitizeColor($state['accent_color'] ?? null, '#14b8a6'),
            'surface_color' => $this->sanitizeColor($state['surface_color'] ?? null, '#f8fafc'),
            'font_heading' => $this->sanitizeFont($state['font_heading'] ?? null, 'Figtree'),
            'font_body' => $this->sanitizeFont($state['font_body'] ?? null, 'Figtree'),
        ])->save();
    }

    public function css(?PlatformSetting $settings = null, ?string $panel = null): string
    {
        $tokens = $this->load($settings, $panel);
        $lines = [':root {'];

        foreach (['primitive', 'semantic', 'component'] as $scope) {
            foreach ($tokens[$scope] as $key => $value) {
                $lines[] = sprintf('    %s: %s;', $key, $value);
            }
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }

    public function styleDictionary(?PlatformSetting $settings = null, ?string $panel = null): array
    {
        $tokens = $this->load($settings, $panel);
        $payload = [];

        foreach ($tokens as $scope => $scopeTokens) {
            foreach ($scopeTokens as $key => $value) {
                Arr::set($payload, $this->styleDictionaryPath($scope, $key), [
                    'value' => $this->styleDictionaryValue($value),
                ]);
            }
        }

        return $payload;
    }

    public function tailwindConfig(?PlatformSetting $settings = null, ?string $panel = null): string
    {
        $config = [
            'theme' => [
                'extend' => [
                    'colors' => [
                        'primary' => 'var(--color-primary)',
                        'secondary' => 'var(--color-secondary)',
                        'accent' => 'var(--color-accent)',
                        'surface' => 'var(--color-surface)',
                        'sidebar' => 'var(--sidebar-bg)',
                    ],
                    'borderRadius' => [
                        'md' => 'var(--radius-md)',
                        'card' => 'var(--card-radius)',
                    ],
                    'boxShadow' => [
                        'card' => 'var(--card-shadow)',
                    ],
                    'fontFamily' => [
                        'sans' => ['var(--app-body-font)'],
                        'heading' => ['var(--app-heading-font)'],
                    ],
                ],
            ],
        ];

        return "export default ".json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).";\n";
    }

    public function resolvedValues(array $tokens): array
    {
        $flat = [];

        foreach ($tokens as $scopeTokens) {
            foreach ($scopeTokens as $key => $value) {
                $flat[$key] = $value;
            }
        }

        $resolved = [];

        foreach ($flat as $key => $value) {
            $resolved[$key] = $this->resolveValue($value, $flat, [$key]);
        }

        return $resolved;
    }

    public function exportPayload(?PlatformSetting $settings = null, ?string $panel = null): array
    {
        return [
            'css' => $this->css($settings, $panel),
            'json' => $this->styleDictionary($settings, $panel),
            'tailwind' => $this->tailwindConfig($settings, $panel),
            'resolved' => $this->resolvedValues($this->load($settings, $panel)),
        ];
    }

    private function applyLegacySettings(array $tokens, PlatformSetting $settings): array
    {
        $tokens['semantic']['--color-primary'] = $this->sanitizeColor($settings->primary_color, $tokens['semantic']['--color-primary']);
        $tokens['semantic']['--color-secondary'] = $this->sanitizeColor($settings->secondary_color, $tokens['semantic']['--color-secondary']);
        $tokens['semantic']['--color-accent'] = $this->sanitizeColor($settings->accent_color, $tokens['semantic']['--color-accent']);
        $tokens['semantic']['--color-surface'] = $this->sanitizeColor($settings->surface_color, $tokens['semantic']['--color-surface']);
        $tokens['semantic']['--font-heading'] = $this->sanitizeFont($settings->font_heading, $tokens['semantic']['--font-heading']);
        $tokens['semantic']['--font-body'] = $this->sanitizeFont($settings->font_body, $tokens['semantic']['--font-body']);
        $tokens['semantic']['--bg-image'] = $this->backgroundImageTokenValue($settings->bg_image_path);

        return $tokens;
    }

    private function syncLegacySettings(PlatformSetting $settings): void
    {
        $defaults = ThemeTokenRegistry::defaults()['semantic'];
        $legacy = [
            '--color-primary' => $this->sanitizeColor($settings->primary_color, '#2563eb'),
            '--color-secondary' => $this->sanitizeColor($settings->secondary_color, '#0f172a'),
            '--color-accent' => $this->sanitizeColor($settings->accent_color, '#14b8a6'),
            '--color-surface' => $this->sanitizeColor($settings->surface_color, '#f8fafc'),
            '--font-heading' => $this->sanitizeFont($settings->font_heading, 'Figtree'),
            '--font-body' => $this->sanitizeFont($settings->font_body, 'Figtree'),
            '--bg-image' => $this->backgroundImageTokenValue($settings->bg_image_path),
        ];

        foreach ($legacy as $key => $value) {
            $token = TitanThemeToken::query()
                ->where('panel', 'global')
                ->where('scope', 'semantic')
                ->where('key', $key)
                ->first();

            if (! $token) {
                continue;
            }

            if ($token->value !== ($defaults[$key] ?? null) || $token->value === $value) {
                continue;
            }

            $token->update(['value' => $value]);
        }
    }

    private function sanitizeEditorValue(string $type, ?string $value, string $default): string
    {
        return match ($type) {
            'color' => $this->sanitizeColor($value, $default),
            'font' => $this->sanitizeFont($value, $default),
            default => $default,
        };
    }

    private function sanitizeColor(?string $value, string $default): string
    {
        if (! is_string($value)) {
            return $default;
        }

        $value = trim($value);

        return preg_match(self::HEX_COLOR_REGEX, $value) === 1 ? strtolower($value) : $default;
    }

    private function sanitizeFont(?string $value, string $default): string
    {
        if (! is_string($value)) {
            return $default;
        }

        $value = trim($value);

        return $value !== '' && preg_match(self::FONT_REGEX, $value) === 1 ? $value : $default;
    }

    private function backgroundImageTokenValue(?string $path): string
    {
        if (! is_string($path) || trim($path) === '' || str_contains($path, '..')) {
            return 'none';
        }

        if (! $this->canUseLaravel()) {
            return 'none';
        }

        $url = Storage::disk('public')->url($path);
        $url = str_replace(["\r", "\n", '"'], '', $url);

        return sprintf('url("%s")', $url);
    }

    private function resolveValue(string $value, array $flat, array $trail): string
    {
        if (! preg_match('/^var\((--[^)]+)\)$/', trim($value), $matches)) {
            return $value;
        }

        $reference = $matches[1];

        if (in_array($reference, $trail, true) || ! array_key_exists($reference, $flat)) {
            return $value;
        }

        return $this->resolveValue($flat[$reference], $flat, [...$trail, $reference]);
    }

    private function styleDictionaryPath(string $scope, string $key): string
    {
        $segments = explode('-', ltrim($key, '-'));

        return implode('.', array_map(static fn (string $segment): string => Str::snake($segment, ''), [$scope, ...$segments]));
    }

    private function styleDictionaryValue(string $value): string
    {
        if (! preg_match('/^var\((--[^)]+)\)$/', trim($value), $matches)) {
            return $value;
        }

        $scope = $this->scopeForKey($matches[1]);

        if ($scope === null) {
            return $value;
        }

        $reference = implode('.', array_map(static fn (string $segment): string => Str::snake($segment, ''), [$scope, ...explode('-', ltrim($matches[1], '-'))]));

        return '{'.$reference.'}';
    }

    private function scopeForKey(string $key): ?string
    {
        foreach (ThemeTokenRegistry::defaults() as $scope => $tokens) {
            if (array_key_exists($key, $tokens)) {
                return $scope;
            }
        }

        return null;
    }

    private function sanitizeStoredToken(string $scope, string $key, string $value): string
    {
        $taxonomy = ThemeTokenRegistry::taxonomy();
        $default = ThemeTokenRegistry::defaults()[$scope][$key] ?? $value;
        $type = $taxonomy[$scope][$key]['type'] ?? null;

        if ($this->isAllowedReference($value)) {
            return $value;
        }

        return match ($type) {
            'color' => $this->sanitizeColor($value, $default),
            'font' => $this->sanitizeFont($value, $default),
            'background' => $this->sanitizeBackground($value, $default),
            default => $this->sanitizeGenericToken($value, $default),
        };
    }

    private function isAllowedReference(string $value): bool
    {
        if (! preg_match('/^var\((--[^)]+)\)$/', trim($value), $matches)) {
            return false;
        }

        return $this->scopeForKey($matches[1]) !== null;
    }

    private function sanitizeBackground(?string $value, string $default): string
    {
        if (! is_string($value)) {
            return $default;
        }

        $value = trim($value);

        if ($value === 'none') {
            return 'none';
        }

        return preg_match('/^url\("[-A-Za-z0-9_\/:.%?=&]+"\)$/', $value) === 1 ? $value : $default;
    }

    private function sanitizeGenericToken(?string $value, string $default): string
    {
        if (! is_string($value)) {
            return $default;
        }

        $value = trim($value);

        return preg_match('/^[A-Za-z0-9#(),.%\s-]+$/', $value) === 1 ? $value : $default;
    }

    private function normalizePanel(?string $panel): string
    {
        return is_string($panel) && trim($panel) !== '' ? trim($panel) : 'global';
    }

    private function canUseLaravel(): bool
    {
        return Facade::getFacadeApplication() !== null;
    }
}
