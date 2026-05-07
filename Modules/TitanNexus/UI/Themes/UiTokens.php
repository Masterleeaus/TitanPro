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

        $presets = [
            'none',
            'fade-in',
            'slide-up',
            'smooth-sidebar',
            'hover-lift',
            'blur-overlay',
            'shimmer-load',
            'scale-press',
        ];

        return [
            'tokens' => [
                '--motion-preset' => 'none',
                '--motion-speed' => $speedTokens['normal'],
                '--motion-ease' => $easeTokens['ease-out'],
            ],
            'speed' => $speedTokens,
            'easing' => $easeTokens,
            'presets' => $presets,
            'generated_css' => self::generatedCss($presets),
            'reduced_motion_media_query' => '(prefers-reduced-motion: reduce)',
        ];
    }

    private static function generatedCss(array $presets): string
    {
        $rules = [];

        foreach ($presets as $preset) {
            if ($preset === 'none') {
                continue;
            }

            $rules[] = match ($preset) {
                'fade-in' => ':root[data-motion-preset="fade-in"] .motion-target { animation: titan-fade-in var(--motion-speed) var(--motion-ease) both; }',
                'slide-up' => ':root[data-motion-preset="slide-up"] .motion-target { animation: titan-slide-up var(--motion-speed) var(--motion-ease) both; }',
                'smooth-sidebar' => ':root[data-motion-preset="smooth-sidebar"] .motion-sidebar { transition: transform var(--motion-speed) var(--motion-ease), width var(--motion-speed) var(--motion-ease); }',
                'hover-lift' => ':root[data-motion-preset="hover-lift"] .motion-card:hover { transform: translateY(-2px); box-shadow: 0 14px 30px -18px rgb(15 23 42 / 45%); transition: transform var(--motion-speed) var(--motion-ease), box-shadow var(--motion-speed) var(--motion-ease); }',
                'blur-overlay' => ':root[data-motion-preset="blur-overlay"] .motion-overlay { backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }',
                'shimmer-load' => ':root[data-motion-preset="shimmer-load"] .motion-skeleton { background-image: linear-gradient(90deg, rgb(226 232 240 / 45%) 0%, rgb(226 232 240 / 90%) 50%, rgb(226 232 240 / 45%) 100%); background-size: 200% 100%; animation: titan-shimmer 1.2s linear infinite; }',
                'scale-press' => ':root[data-motion-preset="scale-press"] .motion-press:active { transform: scale(0.98); transition: transform 120ms var(--motion-ease); }',
                default => '',
            };
        }

        return implode("\n", array_filter([
            ':root { --motion-preset: none; --motion-speed: 250ms; --motion-ease: cubic-bezier(0.16, 1, 0.3, 1); }',
            '@keyframes titan-fade-in { from { opacity: 0; } to { opacity: 1; } }',
            '@keyframes titan-slide-up { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }',
            '@keyframes titan-shimmer { from { background-position: 200% 0; } to { background-position: -200% 0; } }',
            ...$rules,
            '@media (prefers-reduced-motion: reduce) { .motion-target, .motion-sidebar, .motion-card, .motion-overlay, .motion-skeleton, .motion-press { animation: none !important; transition: none !important; transform: none !important; } }',
        ]));
    }
}
