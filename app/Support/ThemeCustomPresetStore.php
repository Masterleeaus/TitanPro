<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ThemeCustomPresetStore
{
    public const FILE = 'theme-manager/custom-presets.json';

    public static function all(): array
    {
        $file = storage_path('app/' . self::FILE);

        if (! File::exists($file)) {
            return [];
        }

        $json = json_decode((string) File::get($file), true);

        return is_array($json) ? $json : [];
    }

    public static function get(string $slug): ?array
    {
        return self::all()[$slug] ?? null;
    }

    public static function save(array $preset, ?string $slug = null): string
    {
        $slug ??= $preset['slug'] ?? Str::slug($preset['label'] ?? $preset['name'] ?? 'custom-preset');
        $slug = 'custom-' . Str::slug(preg_replace('/^custom-/', '', $slug));

        $preset['slug'] = $slug;
        $preset['custom'] = true;
        $preset['category'] = $preset['category'] ?? 'Custom';
        $preset['updated_at'] = now()->toIso8601String();

        $all = self::all();
        $all[$slug] = $preset;

        self::writeAll($all);

        return $slug;
    }

    public static function duplicate(string $sourceSlug, ?string $label = null): ?string
    {
        $preset = ThemePresetManager::presets()[$sourceSlug] ?? self::get($sourceSlug);

        if (! $preset) {
            return null;
        }

        $preset['label'] = $label ?: (($preset['label'] ?? $sourceSlug) . ' Copy');
        $preset['description'] = $preset['description'] ?? 'Custom duplicated preset.';

        return self::save($preset);
    }

    public static function delete(string $slug): bool
    {
        $all = self::all();

        if (! isset($all[$slug])) {
            return false;
        }

        unset($all[$slug]);
        self::writeAll($all);

        return true;
    }

    public static function export(?string $slug = null): string
    {
        $data = $slug ? [$slug => (self::get($slug) ?? ThemePresetManager::presets()[$slug] ?? null)] : self::all();
        $data = array_filter($data);

        $dir = storage_path('app/theme-manager/exports');

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $path = $dir . DIRECTORY_SEPARATOR . 'theme-presets-' . now()->format('Ymd-His') . '.json';

        File::put($path, json_encode([
            'type' => 'titan-theme-presets',
            'exported_at' => now()->toIso8601String(),
            'presets' => $data,
        ], JSON_PRETTY_PRINT));

        return $path;
    }

    public static function import(string $path): array
    {
        if (! File::exists($path)) {
            return ['ok' => false, 'imported' => 0, 'issues' => ['File does not exist.']];
        }

        $json = json_decode((string) File::get($path), true);

        if (! is_array($json)) {
            return ['ok' => false, 'imported' => 0, 'issues' => ['Invalid JSON.']];
        }

        $presets = $json['presets'] ?? $json;

        if (! is_array($presets)) {
            return ['ok' => false, 'imported' => 0, 'issues' => ['No presets found.']];
        }

        $count = 0;

        foreach ($presets as $slug => $preset) {
            if (! is_array($preset)) {
                continue;
            }

            self::save($preset, is_string($slug) ? $slug : null);
            $count++;
        }

        return ['ok' => true, 'imported' => $count, 'issues' => []];
    }

    protected static function writeAll(array $presets): void
    {
        $dir = storage_path('app/theme-manager');

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put(storage_path('app/' . self::FILE), json_encode($presets, JSON_PRETTY_PRINT));
    }
}
