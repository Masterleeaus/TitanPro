<?php

namespace App\Support;

class ThemeAccessibilityEngine
{
    public static function contrastRatio(string $foreground, string $background): float
    {
        $fg = self::rgb($foreground);
        $bg = self::rgb($background);

        if (! $fg || ! $bg) {
            return 0.0;
        }

        $l1 = self::luminance($fg);
        $l2 = self::luminance($bg);

        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);

        return round(($lighter + 0.05) / ($darker + 0.05), 2);
    }

    public static function audit(array $colors): array
    {
        $text = $colors['text'] ?? '#0f172a';
        $background = $colors['background'] ?? '#ffffff';
        $primary = $colors['primary'] ?? '#2563eb';
        $surface = $colors['surface'] ?? '#ffffff';

        $checks = [
            'text_on_background' => self::contrastRatio($text, $background),
            'primary_on_surface' => self::contrastRatio($primary, $surface),
        ];

        $issues = [];

        foreach ($checks as $name => $ratio) {
            if ($ratio < 4.5) {
                $issues[] = "{$name} contrast ratio {$ratio} is below WCAG AA text target 4.5.";
            }
        }

        return [
            'ok' => $issues === [],
            'checks' => $checks,
            'issues' => $issues,
        ];
    }

    public static function css(array $preset = []): string
    {
        $motion = $preset['motion'] ?? 'standard';

        if ($motion === 'reduced') {
            return <<<CSS
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.001ms !important;
        animation-iteration-count: 1 !important;
        scroll-behavior: auto !important;
        transition-duration: 0.001ms !important;
    }
}

CSS;
        }

        return '';
    }

    protected static function rgb(string $hex): ?array
    {
        $hex = trim($hex);

        if (! str_starts_with($hex, '#')) {
            return null;
        }

        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) !== 6) {
            return null;
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    protected static function luminance(array $rgb): float
    {
        $values = array_map(function (int $value): float {
            $channel = $value / 255;

            return $channel <= 0.03928
                ? $channel / 12.92
                : (($channel + 0.055) / 1.055) ** 2.4;
        }, $rgb);

        return 0.2126 * $values[0] + 0.7152 * $values[1] + 0.0722 * $values[2];
    }
}
