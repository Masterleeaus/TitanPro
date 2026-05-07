<?php

namespace Modules\TitanNexus\UI\Themes;


final class UiTokens
{
    public static function make(): array
    {
        $speedTokens = [
            'fast' => '150ms',
            'normal' => '250ms',
            'slow' => '400ms',
        ];

        $easeTokens = [
            'ease-out' => 'cubic-bezier(0.16, 1, 0.3, 1)',
            'spring' => 'cubic-bezier(0.34, 1.56, 0.64, 1)',
            'linear' => 'linear',
        ];

        $presetOptions = [
            'none' => 'None',
            'fade-in' => 'Fade In',
            'slide-up' => 'Slide Up',
            'smooth-sidebar' => 'Smooth Sidebar',
            'hover-lift' => 'Hover Lift',
            'blur-overlay' => 'Blur Overlay',
            'shimmer-load' => 'Shimmer Load',
            'scale-press' => 'Scale Press',
        ];

        $defaultTokens = [
            '--motion-preset' => 'none',
            '--motion-speed' => $speedTokens['normal'],
            '--motion-ease' => $easeTokens['ease-out'],
            '--motion-hover-shadow' => '0 14px 30px -18px rgb(15 23 42 / 45%)',
            '--motion-shimmer-low' => 'rgb(226 232 240 / 45%)',
            '--motion-shimmer-high' => 'rgb(226 232 240 / 90%)',
        ];

        return [
            'tokens' => $defaultTokens,
            'speed' => $speedTokens,
            'easing' => $easeTokens,
            'preset_options' => $presetOptions,
            'speed_options' => [
                $speedTokens['fast'] => 'Fast (150ms)',
                $speedTokens['normal'] => 'Normal (250ms)',
                $speedTokens['slow'] => 'Slow (400ms)',
            ],
            'easing_options' => [
                $easeTokens['ease-out'] => 'Ease Out',
                $easeTokens['spring'] => 'Spring',
                $easeTokens['linear'] => 'Linear',
            ],
            'presets' => array_keys($presetOptions),
            'generated_css' => self::generatedCss(array_keys($presetOptions), $defaultTokens),
            'reduced_motion_media_query' => '(prefers-reduced-motion: reduce)',
        ];
    }

    private static function generatedCss(array $presets, array $tokens): string
    {
        $presetRules = self::presetCssRules();
        $motionTargets = self::motionTargets();
        $rules = [];

        foreach ($presets as $preset) {
            if ($preset === 'none' || ! isset($presetRules[$preset])) {
                continue;
            }

            $rules[] = $presetRules[$preset];
        }

        return implode("\n", array_filter([
            sprintf(
                ':root { --motion-preset: %s; --motion-speed: %s; --motion-ease: %s; --motion-hover-shadow: %s; --motion-shimmer-low: %s; --motion-shimmer-high: %s; }',
                $tokens['--motion-preset'],
                $tokens['--motion-speed'],
                $tokens['--motion-ease'],
                $tokens['--motion-hover-shadow'],
                $tokens['--motion-shimmer-low'],
                $tokens['--motion-shimmer-high']
            ),
            '@keyframes titan-fade-in { from { opacity: 0; } to { opacity: 1; } }',
            '@keyframes titan-slide-up { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }',
            '@keyframes titan-shimmer { from { background-position: 200% 0; } to { background-position: -200% 0; } }',
            ...$rules,
            sprintf(
                '@media (prefers-reduced-motion: reduce) { %s { animation: none !important; transition: none !important; } .motion-overlay { backdrop-filter: none !important; -webkit-backdrop-filter: none !important; } }',
                implode(', ', $motionTargets)
            ),
        ]));
    }

    private static function presetCssRules(): array
    {
        return [
            'fade-in' => ':root[data-motion-preset="fade-in"] .motion-target { animation: titan-fade-in var(--motion-speed) var(--motion-ease) both; }',
            'slide-up' => ':root[data-motion-preset="slide-up"] .motion-target { animation: titan-slide-up var(--motion-speed) var(--motion-ease) both; }',
            'smooth-sidebar' => ':root[data-motion-preset="smooth-sidebar"] .motion-sidebar { transition: transform var(--motion-speed) var(--motion-ease), width var(--motion-speed) var(--motion-ease); }',
            'hover-lift' => ':root[data-motion-preset="hover-lift"] .motion-card:hover, :root[data-motion-preset="hover-lift"] .motion-card:focus-visible { transform: translateY(-2px); box-shadow: var(--motion-hover-shadow); transition: transform var(--motion-speed) var(--motion-ease), box-shadow var(--motion-speed) var(--motion-ease); }',
            'blur-overlay' => ':root[data-motion-preset="blur-overlay"] .motion-overlay { backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }',
            'shimmer-load' => ':root[data-motion-preset="shimmer-load"] .motion-skeleton { background-image: linear-gradient(90deg, var(--motion-shimmer-low) 0%, var(--motion-shimmer-high) 50%, var(--motion-shimmer-low) 100%); background-size: 200% 100%; animation: titan-shimmer var(--motion-speed) linear infinite; }',
            'scale-press' => ':root[data-motion-preset="scale-press"] .motion-press:active { transform: scale(0.98); transition: transform var(--motion-speed) var(--motion-ease); }',
        ];
    }

    private static function motionTargets(): array
    {
        return ['.motion-target', '.motion-sidebar', '.motion-card', '.motion-overlay', '.motion-skeleton', '.motion-press'];
    }
}
