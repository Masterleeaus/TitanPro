<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\PlatformSetting;
use App\Models\OrganizationBranding;
use App\Models\SharedTheme;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

/**
 * Core logic for the Theme Marketplace:
 *  - Built-in curated theme definitions
 *  - ZIP pack / unpack
 *  - Token application to the platform settings layer
 *  - Share link creation / resolution
 */
final class ThemePackManager
{
    // ─────────────────────────────────────────────────────────────────────────
    // Built-in theme catalogue
    // ─────────────────────────────────────────────────────────────────────────

    /** @return array<string, array{name:string, author:string, tags:string[], rating:float, tokens:array<string,string>}> */
    public static function builtinThemes(): array
    {
        return [
            'ocean' => [
                'name'    => 'Ocean',
                'author'  => 'Titan Team',
                'version' => '1.0.0',
                'tags'    => ['blue', 'professional', 'calm'],
                'rating'  => 4.7,
                'tokens'  => [
                    'primary_color'   => '#0369a1',
                    'secondary_color' => '#0c4a6e',
                    'accent_color'    => '#06b6d4',
                    'surface_color'   => '#f0f9ff',
                    'font_heading'    => 'Inter',
                    'font_body'       => 'Inter',
                ],
            ],
            'aurora' => [
                'name'    => 'Aurora',
                'author'  => 'Titan Team',
                'version' => '1.0.0',
                'tags'    => ['purple', 'vibrant', 'gradient'],
                'rating'  => 4.5,
                'tokens'  => [
                    'primary_color'   => '#7c3aed',
                    'secondary_color' => '#4c1d95',
                    'accent_color'    => '#ec4899',
                    'surface_color'   => '#faf5ff',
                    'font_heading'    => 'Figtree',
                    'font_body'       => 'Figtree',
                ],
            ],
            'nordic' => [
                'name'    => 'Nordic',
                'author'  => 'Titan Team',
                'version' => '1.0.0',
                'tags'    => ['minimal', 'cool', 'gray'],
                'rating'  => 4.3,
                'tokens'  => [
                    'primary_color'   => '#3b82f6',
                    'secondary_color' => '#1e293b',
                    'accent_color'    => '#64748b',
                    'surface_color'   => '#f8fafc',
                    'font_heading'    => 'Inter',
                    'font_body'       => 'Inter',
                ],
            ],
            'midnight-neon' => [
                'name'    => 'Midnight Neon',
                'author'  => 'Titan Team',
                'version' => '1.0.0',
                'tags'    => ['dark', 'neon', 'vibrant'],
                'rating'  => 4.6,
                'tokens'  => [
                    'primary_color'   => '#a855f7',
                    'secondary_color' => '#0f0f1a',
                    'accent_color'    => '#22d3ee',
                    'surface_color'   => '#1e1b4b',
                    'font_heading'    => 'Space Grotesk',
                    'font_body'       => 'Space Grotesk',
                ],
            ],
            'emerald-ops' => [
                'name'    => 'Emerald Ops',
                'author'  => 'Titan Team',
                'version' => '1.0.0',
                'tags'    => ['green', 'operations', 'clean'],
                'rating'  => 4.4,
                'tokens'  => [
                    'primary_color'   => '#059669',
                    'secondary_color' => '#064e3b',
                    'accent_color'    => '#34d399',
                    'surface_color'   => '#ecfdf5',
                    'font_heading'    => 'Figtree',
                    'font_body'       => 'Figtree',
                ],
            ],
            'graphite-pro' => [
                'name'    => 'Graphite Pro',
                'author'  => 'Titan Team',
                'version' => '1.0.0',
                'tags'    => ['neutral', 'corporate', 'minimal'],
                'rating'  => 4.2,
                'tokens'  => [
                    'primary_color'   => '#374151',
                    'secondary_color' => '#111827',
                    'accent_color'    => '#6b7280',
                    'surface_color'   => '#f9fafb',
                    'font_heading'    => 'Inter',
                    'font_body'       => 'Inter',
                ],
            ],
            'solarized' => [
                'name'    => 'Solarized',
                'author'  => 'Titan Team',
                'version' => '1.0.0',
                'tags'    => ['warm', 'amber', 'retro'],
                'rating'  => 4.1,
                'tokens'  => [
                    'primary_color'   => '#b45309',
                    'secondary_color' => '#78350f',
                    'accent_color'    => '#f59e0b',
                    'surface_color'   => '#fffbeb',
                    'font_heading'    => 'Georgia',
                    'font_body'       => 'Georgia',
                ],
            ],
            'teal-ops' => [
                'name'    => 'Teal Ops',
                'author'  => 'Titan Team',
                'version' => '1.0.0',
                'tags'    => ['teal', 'operations', 'fresh'],
                'rating'  => 4.5,
                'tokens'  => [
                    'primary_color'   => '#0f766e',
                    'secondary_color' => '#134e4a',
                    'accent_color'    => '#2dd4bf',
                    'surface_color'   => '#f0fdfa',
                    'font_heading'    => 'Figtree',
                    'font_body'       => 'Figtree',
                ],
            ],
            'teal-dark' => [
                'name'    => 'Teal Dark',
                'author'  => 'Titan Team',
                'version' => '1.0.0',
                'tags'    => ['teal', 'dark', 'sleek'],
                'rating'  => 4.3,
                'tokens'  => [
                    'primary_color'   => '#14b8a6',
                    'secondary_color' => '#0f172a',
                    'accent_color'    => '#5eead4',
                    'surface_color'   => '#0f2030',
                    'font_heading'    => 'Inter',
                    'font_body'       => 'Inter',
                ],
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ZIP validation
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Validate a theme pack ZIP.
     *
     * Expects:
     *   theme.json  — token values (primary_color, secondary_color, etc.)
     *   meta.json   — name, author, version, tags
     *   preview.png — preview image (optional but recommended)
     *
     * @return array{ok:bool, error?:string, meta?:array, tokens?:array}
     */
    public function validateZip(string $absolutePath): array
    {
        if (! class_exists(ZipArchive::class)) {
            return ['ok' => false, 'error' => 'ZipArchive PHP extension is not available.'];
        }

        $zip = new ZipArchive();
        $result = $zip->open($absolutePath);

        if ($result !== true) {
            return ['ok' => false, 'error' => 'Cannot open ZIP file (code ' . $result . ').'];
        }

        // Check required files
        $themeJsonIndex = $zip->locateName('theme.json');
        $metaJsonIndex  = $zip->locateName('meta.json');

        if ($themeJsonIndex === false) {
            $zip->close();

            return ['ok' => false, 'error' => 'Missing theme.json in ZIP.'];
        }

        if ($metaJsonIndex === false) {
            $zip->close();

            return ['ok' => false, 'error' => 'Missing meta.json in ZIP.'];
        }

        $tokens = json_decode($zip->getFromIndex($themeJsonIndex), true);
        $meta   = json_decode($zip->getFromIndex($metaJsonIndex), true);
        $zip->close();

        if (! is_array($tokens)) {
            return ['ok' => false, 'error' => 'theme.json is not valid JSON.'];
        }

        if (! is_array($meta) || empty($meta['name'])) {
            return ['ok' => false, 'error' => 'meta.json is not valid JSON or missing "name".'];
        }

        // Sanitize tokens — only allow hex color strings and safe font names
        $sanitized = $this->sanitizeTokens($tokens);

        return ['ok' => true, 'meta' => $meta, 'tokens' => $sanitized];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Apply tokens
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Apply a token set to the active branding layer.
     * Org-scoped branding takes priority when $orgId is supplied.
     */
    public function applyTokens(array $tokens, ?int $orgId = null): void
    {
        $settings = PlatformSetting::current();

        $settingsUpdate = array_filter([
            'primary_color'   => $tokens['primary_color']   ?? null,
            'secondary_color' => $tokens['secondary_color'] ?? null,
            'accent_color'    => $tokens['accent_color']    ?? null,
            'surface_color'   => $tokens['surface_color']   ?? null,
            'font_heading'    => $tokens['font_heading']    ?? null,
            'font_body'       => $tokens['font_body']       ?? null,
        ]);

        $settings->update($settingsUpdate);

        if ($orgId && Schema::hasTable('organization_brandings')) {
            $branding = OrganizationBranding::firstOrCreate(['organization_id' => $orgId]);
            $branding->update(array_filter([
                'primary_color'   => $tokens['primary_color']   ?? null,
                'secondary_color' => $tokens['secondary_color'] ?? null,
                'font_family'     => $tokens['font_heading']    ?? null,
            ]));
        }

        cache()->forget('platform_settings');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ZIP export
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Build a theme pack ZIP from the given token set.
     * Returns the absolute path to the temporary ZIP file.
     *
     * @param  array<string,string> $tokens
     */
    public function buildExportZip(string $name, array $tokens): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new \RuntimeException('ZipArchive PHP extension is required for theme export.');
        }

        $slug    = \Illuminate\Support\Str::slug($name);
        $tmpPath = sys_get_temp_dir() . '/titan-theme-' . $slug . '-' . time() . '.zip';

        $zip = new ZipArchive();
        $zip->open($tmpPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $zip->addFromString('theme.json', json_encode($tokens, JSON_PRETTY_PRINT));
        $zip->addFromString('meta.json', json_encode([
            'name'    => $name,
            'author'  => config('app.name', 'Titan'),
            'version' => '1.0.0',
            'tags'    => [],
        ], JSON_PRETTY_PRINT));

        // Add a minimal 1×1 transparent PNG as placeholder preview
        $zip->addFromString('preview.png', base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='
        ));

        $zip->close();

        return $tmpPath;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Share / import
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Store a shared theme and return the share token.
     *
     * @param  array<string,string> $tokens
     */
    public function createShareToken(string $name, ?string $author, array $tokens): string
    {
        $shared = SharedTheme::createFromTokens($name, $author, $tokens);

        return $shared->token;
    }

    /**
     * Resolve a share token URL and return theme data, or null if not found.
     * Also increments the view counter.
     *
     * @return array{meta:array, tokens:array}|null
     */
    public function resolveShareToken(string $token): ?array
    {
        if (! Schema::hasTable('shared_themes')) {
            return null;
        }

        $shared = SharedTheme::where('token', $token)->first();

        if (! $shared) {
            return null;
        }

        $shared->increment('views');

        return [
            'meta' => [
                'name'    => $shared->name,
                'author'  => $shared->author ?? 'Unknown',
                'version' => '1.0.0',
                'tags'    => [],
            ],
            'tokens' => $shared->tokens,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private const HEX_REGEX = '/^#[0-9a-fA-F]{3}(?:[0-9a-fA-F]{3})?$/';
    private const FONT_REGEX = '/^[\w\s\-]+$/';

    /**
     * Sanitize a raw token map — only hex colors and safe font names pass through.
     *
     * @param  array<string, mixed> $raw
     * @return array<string, string>
     */
    public function sanitizeTokens(array $raw): array
    {
        $colorKeys = ['primary_color', 'secondary_color', 'accent_color', 'surface_color'];
        $fontKeys  = ['font_heading', 'font_body'];

        $out = [];

        foreach ($colorKeys as $key) {
            if (isset($raw[$key]) && is_string($raw[$key]) && preg_match(self::HEX_REGEX, $raw[$key])) {
                $out[$key] = $raw[$key];
            }
        }

        foreach ($fontKeys as $key) {
            if (isset($raw[$key]) && is_string($raw[$key]) && preg_match(self::FONT_REGEX, $raw[$key])) {
                $out[$key] = mb_substr($raw[$key], 0, 120);
            }
        }

        return $out;
    }
}
