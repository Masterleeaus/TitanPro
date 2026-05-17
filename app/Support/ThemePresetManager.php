<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ThemePresetManager
{
    public const CACHE_KEY = 'theme_manager.active_preset';
    public const MARKER_FILE = 'theme-manager/active-preset.json';

    public static function builtIns(): array
    {
        return [
            'system-clean' => [
                'label' => 'System Clean',
                'description' => 'Neutral enterprise UI with balanced contrast.',
                'mode' => 'system',
                'category' => 'Core',
                'colors' => ['primary' => '#2563eb', 'secondary' => '#64748b', 'accent' => '#22c55e', 'surface' => '#ffffff', 'background' => '#f8fafc', 'text' => '#0f172a'],
                'radius' => '0.75rem',
                'density' => 'comfortable',
                'motion' => 'standard',
            ],
            'titan-dark' => [
                'label' => 'Titan Dark',
                'description' => 'Dark panel preset with strong blue accents.',
                'mode' => 'dark',
                'category' => 'Dark',
                'colors' => ['primary' => '#60a5fa', 'secondary' => '#94a3b8', 'accent' => '#38bdf8', 'surface' => '#111827', 'background' => '#030712', 'text' => '#f8fafc'],
                'radius' => '0.875rem',
                'density' => 'comfortable',
                'motion' => 'standard',
            ],
            'creator-violet' => [
                'label' => 'Creator Violet',
                'description' => 'Violet/pink creator dashboard styling.',
                'mode' => 'system',
                'category' => 'Creative',
                'colors' => ['primary' => '#8b5cf6', 'secondary' => '#64748b', 'accent' => '#ec4899', 'surface' => '#ffffff', 'background' => '#faf5ff', 'text' => '#1e1b4b'],
                'radius' => '1rem',
                'density' => 'comfortable',
                'motion' => 'expressive',
            ],
            'tradie-amber' => [
                'label' => 'Tradie Amber',
                'description' => 'Warm amber/stone preset for field-service style apps.',
                'mode' => 'light',
                'category' => 'Industry',
                'colors' => ['primary' => '#f59e0b', 'secondary' => '#78716c', 'accent' => '#ea580c', 'surface' => '#ffffff', 'background' => '#fffbeb', 'text' => '#1c1917'],
                'radius' => '0.5rem',
                'density' => 'compact',
                'motion' => 'standard',
            ],
            'accessible-high-contrast' => [
                'label' => 'Accessible High Contrast',
                'description' => 'High contrast preset with reduced motion defaults.',
                'mode' => 'system',
                'category' => 'Accessibility',
                'colors' => ['primary' => '#1d4ed8', 'secondary' => '#334155', 'accent' => '#047857', 'surface' => '#ffffff', 'background' => '#ffffff', 'text' => '#020617'],
                'radius' => '0.375rem',
                'density' => 'comfortable',
                'motion' => 'reduced',
            ],
        ];
    }

    public static function presets(): array
    {
        return array_replace_recursive(self::builtIns(), ThemeCustomPresetStore::all());
    }

    public static function options(): array
    {
        return collect(self::presets())->mapWithKeys(fn (array $preset, string $key): array => [$key => $preset['label'] ?? str($key)->headline()->toString()])->all();
    }

    public static function gallery(): array
    {
        $active = self::activePresetSlug();

        return collect(self::presets())
            ->map(function (array $preset, string $slug) use ($active): array {
                $preset['slug'] = $slug;
                $preset['active'] = $slug === $active;
                $preset['preview_css'] = self::inlinePreviewStyle($preset);

                return $preset;
            })
            ->values()
            ->all();
    }

    public static function activePresetSlug(): string
    {
        $cached = Cache::get(self::CACHE_KEY);

        if (is_string($cached) && array_key_exists($cached, self::presets())) {
            return $cached;
        }

        $marker = self::readMarker(storage_path('app/' . self::MARKER_FILE));

        foreach ([$marker, config('theme-presets.active'), 'system-clean'] as $candidate) {
            if (is_string($candidate) && array_key_exists($candidate, self::presets())) {
                Cache::put(self::CACHE_KEY, $candidate, now()->addMinutes(10));
                return $candidate;
            }
        }

        return 'system-clean';
    }

    public static function activePreset(): array
    {
        return self::presets()[self::activePresetSlug()] ?? self::presets()['system-clean'];
    }

    public static function setActivePreset(string $slug): bool
    {
        if (! array_key_exists($slug, self::presets())) {
            return false;
        }

        $dir = storage_path('app/theme-manager');

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put(storage_path('app/' . self::MARKER_FILE), json_encode(['slug' => $slug, 'updated_at' => now()->toIso8601String()], JSON_PRETTY_PRINT));
        Cache::forget(self::CACHE_KEY);
        Cache::put(self::CACHE_KEY, $slug, now()->addMinutes(10));

        return true;
    }

    public static function cssVariables(?string $slug = null): string
    {
        $preset = $slug && isset(self::presets()[$slug]) ? self::presets()[$slug] : self::activePreset();
        $colors = $preset['colors'] ?? [];

        $primary = $colors['primary'] ?? '#2563eb';
        $secondary = $colors['secondary'] ?? '#64748b';
        $accent = $colors['accent'] ?? '#22c55e';
        $surface = $colors['surface'] ?? '#ffffff';
        $background = $colors['background'] ?? '#f8fafc';
        $text = $colors['text'] ?? '#0f172a';
        $radius = $preset['radius'] ?? '0.75rem';

        return <<<CSS
:root {
    --theme-preset-primary: {$primary};
    --theme-preset-secondary: {$secondary};
    --theme-preset-accent: {$accent};
    --theme-preset-surface: {$surface};
    --theme-preset-background: {$background};
    --theme-preset-text: {$text};
    --theme-preset-radius: {$radius};
    --theme-primary-color: var(--theme-preset-primary);
    --theme-secondary-color: var(--theme-preset-secondary);
    --theme-accent-color: var(--theme-preset-accent);
}

CSS;
    }

    public static function inlinePreviewStyle(array $preset): string
    {
        $colors = $preset['colors'] ?? [];
        return sprintf('background: linear-gradient(135deg, %s 0%%, %s 52%%, %s 100%%); color: %s;', $colors['primary'] ?? '#2563eb', $colors['secondary'] ?? '#64748b', $colors['accent'] ?? '#22c55e', $colors['text'] ?? '#ffffff');
    }

    public static function diagnostics(): array
    {
        return ['active' => self::activePresetSlug(), 'preset' => self::activePreset(), 'available' => array_keys(self::presets()), 'custom_count' => count(ThemeCustomPresetStore::all()), 'count' => count(self::presets())];
    }

    protected static function readMarker(string $file): ?string
    {
        if (! File::exists($file)) {
            return null;
        }

        $raw = trim((string) File::get($file));
        $json = json_decode($raw, true);

        return is_array($json) ? ($json['slug'] ?? null) : ($raw ?: null);
    }
}
