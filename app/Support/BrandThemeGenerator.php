<?php

declare(strict_types=1);

namespace App\Support;

class BrandThemeGenerator
{
    public function generate(array $input): array
    {
        $logoColors = $this->extractDominantColorsFromImage($input['logo_absolute_path'] ?? null);
        $wallpaperColors = $this->extractDominantColorsFromImage($input['wallpaper_absolute_path'] ?? null);

        $accentColor = $this->normalizeHexColor($input['accent_color'] ?? null);
        $primaryColor = $logoColors[0] ?? $accentColor ?? '#2563eb';
        $secondaryColor = $accentColor ?? ($logoColors[1] ?? '#0f172a');
        $surfaceColor = $wallpaperColors[0] ?? $this->mix($primaryColor, '#ffffff', 0.9);

        $textColor = $this->bestTextColorForBackground($surfaceColor);
        $contrastRatio = $this->contrastRatio($textColor, $surfaceColor);
        $wcagWarning = $contrastRatio < 4.5
            ? sprintf('Generated text/background contrast ratio %.2f:1 fails WCAG AA (4.5:1).', $contrastRatio)
            : null;

        $fontFamily = $this->fontFamilyFromGoogleUrl($input['font_source_url'] ?? null)
            ?? $this->fontFamilyFromUploadedFile($input['font_file_path'] ?? null)
            ?? 'Figtree';

        return [
            'primary_color' => $primaryColor,
            'secondary_color' => $secondaryColor,
            'surface_color' => $surfaceColor,
            'font_heading' => $fontFamily,
            'font_body' => $fontFamily,
            'wcag_warning' => $wcagWarning,
            'contrast_ratio' => round($contrastRatio, 2),
            'text_color' => $textColor,
        ];
    }

    public function sanitizeGoogleFontsUrl(?string $url): ?string
    {
        if (! is_string($url) || trim($url) === '') {
            return null;
        }

        $url = trim($url);
        $parts = parse_url($url);

        if (! is_array($parts)) {
            return null;
        }

        $scheme = strtolower($parts['scheme'] ?? '');
        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';

        if ($scheme !== 'https' || $host !== 'fonts.googleapis.com' || ! str_starts_with($path, '/css')) {
            return null;
        }

        return $url;
    }

    public function fontFamilyFromGoogleUrl(?string $url): ?string
    {
        $url = $this->sanitizeGoogleFontsUrl($url);

        if (! $url) {
            return null;
        }

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        $family = $query['family'] ?? null;

        if (! is_string($family) || $family === '') {
            return null;
        }

        $firstFamily = explode('|', $family)[0];
        $firstFamily = explode(':', $firstFamily)[0];
        $firstFamily = str_replace('+', ' ', $firstFamily);

        return trim($firstFamily) ?: null;
    }

    public function fontFamilyFromUploadedFile(?string $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        $name = pathinfo($path, PATHINFO_FILENAME);
        $name = preg_replace('/[-_]+/', ' ', $name ?? '');
        $name = preg_replace('/\s+/', ' ', (string) $name);
        $name = trim((string) $name);

        return $name !== '' ? ucwords($name) : null;
    }

    public function extractDominantColorsFromImage(?string $absolutePath, int $limit = 3): array
    {
        if (! is_string($absolutePath) || $absolutePath === '' || ! is_file($absolutePath)) {
            return [];
        }

        $extension = strtolower((string) pathinfo($absolutePath, PATHINFO_EXTENSION));

        if ($extension === 'svg') {
            return $this->extractColorsFromSvg($absolutePath, $limit);
        }

        if (! function_exists('imagecreatefromstring')) {
            return [];
        }

        $imageData = @file_get_contents($absolutePath);

        if ($imageData === false) {
            return [];
        }

        $image = @imagecreatefromstring($imageData);

        if (! $image) {
            return [];
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $step = max(1, (int) floor(sqrt(($width * $height) / 6000)));
        $counts = [];

        for ($y = 0; $y < $height; $y += $step) {
            for ($x = 0; $x < $width; $x += $step) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $hex = $this->quantizedHex($r, $g, $b);
                $counts[$hex] = ($counts[$hex] ?? 0) + 1;
            }
        }

        imagedestroy($image);

        arsort($counts);

        return array_slice(array_keys($counts), 0, $limit);
    }

    private function extractColorsFromSvg(string $path, int $limit): array
    {
        $contents = @file_get_contents($path);

        if ($contents === false) {
            return [];
        }

        preg_match_all('/(?:fill|stroke)\s*=\s*"([^"]+)"/i', $contents, $matches);
        $rawColors = $matches[1] ?? [];
        $colors = [];

        foreach ($rawColors as $rawColor) {
            $hex = $this->normalizeHexColor($rawColor);

            if ($hex) {
                $colors[$hex] = true;
            }
        }

        return array_slice(array_keys($colors), 0, $limit);
    }

    private function quantizedHex(int $r, int $g, int $b): string
    {
        $r = (int) round($r / 16) * 16;
        $g = (int) round($g / 16) * 16;
        $b = (int) round($b / 16) * 16;

        return sprintf('#%02x%02x%02x', min($r, 255), min($g, 255), min($b, 255));
    }

    private function bestTextColorForBackground(string $background): string
    {
        $dark = '#111827';
        $light = '#ffffff';

        return $this->contrastRatio($dark, $background) >= $this->contrastRatio($light, $background)
            ? $dark
            : $light;
    }

    private function contrastRatio(string $hexOne, string $hexTwo): float
    {
        $lumOne = $this->relativeLuminance($hexOne);
        $lumTwo = $this->relativeLuminance($hexTwo);

        $lighter = max($lumOne, $lumTwo);
        $darker = min($lumOne, $lumTwo);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    private function relativeLuminance(string $hex): float
    {
        [$r, $g, $b] = $this->hexToRgb($hex);

        $components = array_map(function (int $component): float {
            $channel = $component / 255;

            return $channel <= 0.03928
                ? $channel / 12.92
                : (($channel + 0.055) / 1.055) ** 2.4;
        }, [$r, $g, $b]);

        return (0.2126 * $components[0]) + (0.7152 * $components[1]) + (0.0722 * $components[2]);
    }

    private function normalizeHexColor(?string $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        if (preg_match('/^#([a-f0-9]{3})$/i', $value, $short)) {
            $expanded = implode('', array_map(static fn ($char) => $char . $char, str_split(strtolower($short[1]))));

            return '#'.$expanded;
        }

        if (preg_match('/^#([a-f0-9]{6})$/i', $value, $full)) {
            return '#'.strtolower($full[1]);
        }

        if (preg_match('/^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/i', $value, $rgb)) {
            return sprintf('#%02x%02x%02x', min((int) $rgb[1], 255), min((int) $rgb[2], 255), min((int) $rgb[3], 255));
        }

        return null;
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) !== 6) {
            $hex = '111827';
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    private function mix(string $hexOne, string $hexTwo, float $ratio): string
    {
        $ratio = max(0, min(1, $ratio));
        [$r1, $g1, $b1] = $this->hexToRgb($hexOne);
        [$r2, $g2, $b2] = $this->hexToRgb($hexTwo);

        $r = (int) round(($r1 * (1 - $ratio)) + ($r2 * $ratio));
        $g = (int) round(($g1 * (1 - $ratio)) + ($g2 * $ratio));
        $b = (int) round(($b1 * (1 - $ratio)) + ($b2 * $ratio));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}
