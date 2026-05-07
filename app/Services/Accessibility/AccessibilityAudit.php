<?php

namespace App\Services\Accessibility;

use App\Models\PlatformSetting;
use App\Models\TitanAccessibilityReport;

class AccessibilityAudit
{
    public function audit(?PlatformSetting $settings = null): array
    {
        $settings ??= PlatformSetting::current();

        $tokens = $this->tokens($settings);
        $dismissedChecks = array_values(array_unique(array_filter((array) ($settings->accessibility_dismissals ?? []))));
        $checks = [];

        $textContrast = round($this->contrastRatio($tokens['secondary_color'], $tokens['background_color']), 2);
        $checks[] = $this->makeCheck(
            key: 'text_background_contrast',
            label: 'Text/background contrast',
            standard: 'WCAG AA (4.5:1 normal, 3:1 large)',
            passed: $textContrast >= 4.5,
            affectedTokens: ['secondary_color', 'background_color'],
            value: "{$textContrast}:1",
            target: '4.5:1'
        );

        $buttonContrast = round($this->contrastRatio($tokens['button_text_color'], $tokens['primary_color']), 2);
        $checks[] = $this->makeCheck(
            key: 'button_text_contrast',
            label: 'Button text contrast',
            standard: 'WCAG AA',
            passed: $buttonContrast >= 4.5,
            affectedTokens: ['primary_color', 'button_text_color'],
            value: "{$buttonContrast}:1",
            target: '4.5:1'
        );

        $focusContrast = round($this->contrastRatio($tokens['focus_ring_color'], $tokens['background_color']), 2);
        $checks[] = $this->makeCheck(
            key: 'focus_ring_visibility',
            label: 'Focus ring visibility',
            standard: 'Must be visible on all interactive elements',
            passed: $focusContrast >= 3,
            affectedTokens: ['focus_ring_color'],
            value: "{$focusContrast}:1",
            target: '3:1'
        );

        $baseFontSize = round(16 * $tokens['font_scale'], 1);
        $checks[] = $this->makeCheck(
            key: 'font_size_minimum',
            label: 'Font size minimum',
            standard: 'Body text ≥ 14px',
            passed: $baseFontSize >= 14,
            affectedTokens: ['font_scale'],
            value: "{$baseFontSize}px",
            target: '14px'
        );

        $customCss = strtolower((string) ($settings->custom_css ?? ''));
        $keyboardNavigationPasses = ! preg_match('/tabindex\s*=\s*["\']?[1-9]/', $customCss);
        $checks[] = $this->makeCheck(
            key: 'keyboard_navigation_order',
            label: 'Keyboard navigation order',
            standard: 'Logical tab order',
            passed: (bool) $keyboardNavigationPasses,
            affectedTokens: ['document-order'],
            value: $keyboardNavigationPasses ? 'No positive tabindex override detected in managed theme overrides.' : 'Positive tabindex override detected.',
            target: 'Logical DOM order'
        );

        $checks[] = $this->makeCheck(
            key: 'prefers_reduced_motion',
            label: '`prefers-reduced-motion`',
            standard: 'Animations disabled when OS setting active',
            passed: true,
            affectedTokens: ['motion-reduction'],
            value: 'Accessibility engine injects reduced-motion CSS for animations and scrolling.',
            target: 'Reduce motion automatically'
        );

        $colorSchemeSupport = str_contains(
            (string) @file_get_contents(resource_path('js/composables/useAppearance.ts')),
            'prefers-color-scheme'
        );
        $checks[] = $this->makeCheck(
            key: 'prefers_color_scheme',
            label: '`prefers-color-scheme`',
            standard: 'Dark/light mode respects OS preference',
            passed: $colorSchemeSupport,
            affectedTokens: ['appearance'],
            value: $colorSchemeSupport ? 'System appearance hook detected.' : 'System appearance hook missing.',
            target: 'Match OS color scheme'
        );

        $checks = array_map(function (array $check) use ($dismissedChecks): array {
            $check['dismissed'] = in_array($check['key'], $dismissedChecks, true);

            return $check;
        }, $checks);

        $passCount = count(array_filter($checks, fn (array $check): bool => $check['passed']));
        $dismissedCount = count(array_filter($checks, fn (array $check): bool => $check['dismissed']));

        return [
            'tokens' => $tokens,
            'checks' => $checks,
            'dismissed_checks' => $dismissedChecks,
            'summary' => [
                'total' => count($checks),
                'passed' => $passCount,
                'failed' => count($checks) - $passCount,
                'dismissed' => $dismissedCount,
            ],
            'generated_at' => now()->toIso8601String(),
        ];
    }

    public function record(?PlatformSetting $settings = null, ?array $audit = null, array $appliedFixes = []): TitanAccessibilityReport
    {
        $settings ??= PlatformSetting::current();
        $audit ??= $this->audit($settings);

        return TitanAccessibilityReport::query()->create([
            'platform_setting_id' => $settings->getKey(),
            'theme' => config('theme.active') ?: 'platform',
            'tokens' => $audit['tokens'],
            'checks' => $audit['checks'],
            'summary' => $audit['summary'],
            'dismissed_checks' => $audit['dismissed_checks'],
            'applied_fixes' => $appliedFixes,
        ]);
    }

    public function autoFix(?PlatformSetting $settings = null): array
    {
        $settings ??= PlatformSetting::current();
        $tokens = $this->tokens($settings);
        $fixes = [];

        if ($this->contrastRatio($tokens['secondary_color'], $tokens['background_color']) < 4.5) {
            $fixedTextColor = $this->nearestAccessibleForeground($tokens['secondary_color'], $tokens['background_color'], 4.5);

            $settings->secondary_color = $fixedTextColor;
            $fixes['secondary_color'] = "Adjusted text color to {$fixedTextColor} for compliant contrast.";
        }

        if ($this->contrastRatio($tokens['button_text_color'], $tokens['primary_color']) < 4.5) {
            $fixedButtonText = $this->bestTextColor($tokens['primary_color']);

            $settings->button_text_color = $fixedButtonText;
            $fixes['button_text_color'] = "Adjusted button text color to {$fixedButtonText}.";
        }

        if ($this->contrastRatio($tokens['focus_ring_color'], $tokens['background_color']) < 3) {
            $fixedFocusRing = $this->nearestAccessibleForeground($tokens['focus_ring_color'], $tokens['background_color'], 3);

            $settings->focus_ring_color = $fixedFocusRing;
            $fixes['focus_ring_color'] = "Adjusted focus ring color to {$fixedFocusRing}.";
        }

        if ($tokens['font_scale'] < 1.0) {
            $settings->font_scale = 1.0;
            $fixes['font_scale'] = 'Reset font scale to 1.0.';
        }

        if ($settings->isDirty()) {
            $settings->save();
            cache()->forget('platform_settings');
        }

        $audit = $this->audit($settings->fresh() ?? $settings);
        $report = $this->record($settings->fresh() ?? $settings, $audit, $fixes);

        return [
            'audit' => $audit,
            'fixes' => $fixes,
            'report' => $report,
        ];
    }

    public function themeStyles(?PlatformSetting $settings = null): string
    {
        $tokens = $this->tokens($settings ?? PlatformSetting::current());
        $focusRingShadow = $this->rgba($tokens['focus_ring_color'], 0.22);

        return trim(implode(PHP_EOL, [
            ':root {',
            "    --font-scale: {$tokens['font_scale']};",
            "    --titan-primary-color: {$tokens['primary_color']};",
            "    --titan-text-color: {$tokens['secondary_color']};",
            "    --titan-background-color: {$tokens['background_color']};",
            "    --titan-button-text-color: {$tokens['button_text_color']};",
            "    --titan-focus-ring-color: {$tokens['focus_ring_color']};",
            '    color-scheme: light dark;',
            '}',
            '',
            'html {',
            '    font-size: calc(16px * var(--font-scale));',
            '}',
            '',
            'body {',
            '    color: var(--titan-text-color);',
            '    background-color: var(--titan-background-color);',
            '}',
            '',
            '.fi-btn-color-primary,',
            '.fi-btn-color-primary .fi-btn-label,',
            '.fi-btn-color-primary span {',
            '    color: var(--titan-button-text-color) !important;',
            '}',
            '',
            ':where(a, button, input, select, textarea, summary, [tabindex]):focus-visible {',
            '    outline: 2px solid var(--titan-focus-ring-color) !important;',
            '    outline-offset: 2px;',
            "    box-shadow: 0 0 0 4px {$focusRingShadow} !important;",
            '}',
            '',
            '@media (prefers-reduced-motion: reduce) {',
            '    *,',
            '    *::before,',
            '    *::after {',
            '        animation-duration: 0.01ms !important;',
            '        animation-iteration-count: 1 !important;',
            '        transition-duration: 0.01ms !important;',
            '        scroll-behavior: auto !important;',
            '    }',
            '}',
        ]));
    }

    private function tokens(PlatformSetting $settings): array
    {
        $primaryColor = $this->sanitizeColor($settings->primary_color, '#2563eb');

        return [
            'primary_color' => $primaryColor,
            'secondary_color' => $this->sanitizeColor($settings->secondary_color, '#0f172a'),
            'background_color' => $this->sanitizeColor($settings->background_color, '#ffffff'),
            'button_text_color' => $this->sanitizeColor($settings->button_text_color, $this->bestTextColor($primaryColor)),
            'focus_ring_color' => $this->sanitizeColor($settings->focus_ring_color, $primaryColor),
            'font_scale' => $this->normalizeFontScale($settings->font_scale),
        ];
    }

    private function makeCheck(
        string $key,
        string $label,
        string $standard,
        bool $passed,
        array $affectedTokens,
        string $value,
        string $target
    ): array {
        return [
            'key' => $key,
            'label' => $label,
            'standard' => $standard,
            'passed' => $passed,
            'dismissed' => false,
            'affected_tokens' => $affectedTokens,
            'value' => $value,
            'target' => $target,
        ];
    }

    private function normalizeFontScale(mixed $fontScale): float
    {
        $fontScale = is_numeric($fontScale) ? (float) $fontScale : 1.0;

        return max(0.8, min(1.4, round($fontScale, 1)));
    }

    private function sanitizeColor(mixed $value, string $fallback): string
    {
        $value = is_string($value) ? trim($value) : '';

        if (! preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {
            return $fallback;
        }

        if (strlen($value) === 4) {
            return sprintf(
                '#%s%s%s%s%s%s',
                $value[1],
                $value[1],
                $value[2],
                $value[2],
                $value[3],
                $value[3],
            );
        }

        return strtolower($value);
    }

    private function contrastRatio(string $foregroundHex, string $backgroundHex): float
    {
        $foreground = $this->luminance($foregroundHex);
        $background = $this->luminance($backgroundHex);

        $lighter = max($foreground, $background);
        $darker = min($foreground, $background);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    private function luminance(string $hex): float
    {
        [$red, $green, $blue] = $this->rgb($hex);

        $channels = array_map(function (int $channel): float {
            $channel = $channel / 255;

            return $channel <= 0.03928
                ? $channel / 12.92
                : (($channel + 0.055) / 1.055) ** 2.4;
        }, [$red, $green, $blue]);

        return ($channels[0] * 0.2126) + ($channels[1] * 0.7152) + ($channels[2] * 0.0722);
    }

    private function rgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    private function bestTextColor(string $backgroundColor): string
    {
        $whiteRatio = $this->contrastRatio('#ffffff', $backgroundColor);
        $blackRatio = $this->contrastRatio('#000000', $backgroundColor);

        return $whiteRatio >= $blackRatio ? '#ffffff' : '#000000';
    }

    private function nearestAccessibleForeground(string $foregroundColor, string $backgroundColor, float $targetRatio): string
    {
        if ($this->contrastRatio($foregroundColor, $backgroundColor) >= $targetRatio) {
            return $foregroundColor;
        }

        $candidateAnchors = ['#000000', '#ffffff'];
        $bestColor = $foregroundColor;
        $bestDistance = null;

        foreach ($candidateAnchors as $anchor) {
            for ($step = 0; $step <= 100; $step++) {
                $candidate = $this->mix($foregroundColor, $anchor, $step / 100);

                if ($this->contrastRatio($candidate, $backgroundColor) < $targetRatio) {
                    continue;
                }

                $distance = $this->distance($foregroundColor, $candidate);

                if ($bestDistance === null || $distance < $bestDistance) {
                    $bestDistance = $distance;
                    $bestColor = $candidate;
                }

                break;
            }
        }

        return $bestColor;
    }

    private function mix(string $from, string $to, float $weight): string
    {
        [$fromRed, $fromGreen, $fromBlue] = $this->rgb($from);
        [$toRed, $toGreen, $toBlue] = $this->rgb($to);

        $red = (int) round(($fromRed * (1 - $weight)) + ($toRed * $weight));
        $green = (int) round(($fromGreen * (1 - $weight)) + ($toGreen * $weight));
        $blue = (int) round(($fromBlue * (1 - $weight)) + ($toBlue * $weight));

        return sprintf('#%02x%02x%02x', $red, $green, $blue);
    }

    private function distance(string $first, string $second): float
    {
        [$firstRed, $firstGreen, $firstBlue] = $this->rgb($first);
        [$secondRed, $secondGreen, $secondBlue] = $this->rgb($second);

        return sqrt(
            (($firstRed - $secondRed) ** 2)
            + (($firstGreen - $secondGreen) ** 2)
            + (($firstBlue - $secondBlue) ** 2)
        );
    }

    private function rgba(string $hex, float $alpha): string
    {
        [$red, $green, $blue] = $this->rgb($hex);

        return sprintf('rgba(%d, %d, %d, %.2f)', $red, $green, $blue, $alpha);
    }
}
