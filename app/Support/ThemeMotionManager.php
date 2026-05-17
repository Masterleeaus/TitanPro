<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ThemeMotionManager
{
    public const CACHE_KEY = 'theme_manager.motion_preset';
    public const MARKER_FILE = 'theme-manager/motion-preset.json';

    public static function presets(): array
    {
        return [
            'none' => [
                'label' => 'None / Reduced',
                'duration' => '0ms',
                'easing' => 'linear',
                'scale' => '1',
            ],
            'standard' => [
                'label' => 'Standard',
                'duration' => '150ms',
                'easing' => 'cubic-bezier(.4,0,.2,1)',
                'scale' => '1',
            ],
            'smooth' => [
                'label' => 'Smooth',
                'duration' => '220ms',
                'easing' => 'cubic-bezier(.22,1,.36,1)',
                'scale' => '1.01',
            ],
            'expressive' => [
                'label' => 'Expressive',
                'duration' => '280ms',
                'easing' => 'cubic-bezier(.34,1.56,.64,1)',
                'scale' => '1.015',
            ],
        ];
    }

    public static function options(): array
    {
        return collect(self::presets())->mapWithKeys(fn (array $preset, string $key) => [$key => $preset['label']])->all();
    }

    public static function active(): string
    {
        $cached = Cache::get(self::CACHE_KEY);

        if (is_string($cached) && isset(self::presets()[$cached])) {
            return $cached;
        }

        $file = storage_path('app/' . self::MARKER_FILE);

        if (File::exists($file)) {
            $json = json_decode((string) File::get($file), true);
            $slug = is_array($json) ? ($json['slug'] ?? null) : null;

            if (is_string($slug) && isset(self::presets()[$slug])) {
                Cache::put(self::CACHE_KEY, $slug, now()->addMinutes(10));

                return $slug;
            }
        }

        return 'standard';
    }

    public static function set(string $slug): bool
    {
        if (! isset(self::presets()[$slug])) {
            return false;
        }

        $dir = storage_path('app/theme-manager');

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put(storage_path('app/' . self::MARKER_FILE), json_encode([
            'slug' => $slug,
            'updated_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT));

        Cache::put(self::CACHE_KEY, $slug, now()->addMinutes(10));

        return true;
    }

    public static function css(): string
    {
        $preset = self::presets()[self::active()];

        $duration = $preset['duration'];
        $easing = $preset['easing'];
        $scale = $preset['scale'];

        return <<<CSS
:root {
    --theme-motion-duration: {$duration};
    --theme-motion-easing: {$easing};
    --theme-motion-scale: {$scale};
}

.fi-btn,
.fi-link,
.fi-sidebar-item-button,
.fi-dropdown-panel,
.fi-modal-window {
    transition-duration: var(--theme-motion-duration);
    transition-timing-function: var(--theme-motion-easing);
}

@media (prefers-reduced-motion: reduce) {
    :root {
        --theme-motion-duration: 0ms;
        --theme-motion-scale: 1;
    }
}

CSS;
    }
}
