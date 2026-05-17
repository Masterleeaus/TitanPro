<?php

namespace App\Support;

class ThemePalette
{
    public static function presets(): array
    {
        return [
            'ocean-midnight' => [
                'label' => 'Ocean Midnight', 'description' => 'Deep navy, cyan glass, cool slate and soft ocean glow.',
                'primary' => '#38bdf8', 'primary_2' => '#0ea5e9', 'secondary' => '#2563eb', 'secondary_2' => '#1d4ed8',
                'accent' => '#22d3ee', 'accent_2' => '#14b8a6', 'dark' => '#020617', 'dark_2' => '#07111f',
                'gray' => '#64748b', 'gray_2' => '#94a3b8', 'bg' => '#06111d', 'surface' => '#101b29',
                'surface_2' => '#14263a', 'surface_3' => '#1c334d', 'border' => '#24425f', 'text' => '#eff6ff',
                'muted' => '#a9c5df', 'font' => 'Inter', 'radius' => '16px',
            ],
            'violet-graphite' => [
                'label' => 'Violet Graphite', 'description' => 'Royal violet, indigo tone, graphite shell and lavender mist.',
                'primary' => '#a78bfa', 'primary_2' => '#8b5cf6', 'secondary' => '#6366f1', 'secondary_2' => '#4f46e5',
                'accent' => '#c084fc', 'accent_2' => '#e879f9', 'dark' => '#0b0911', 'dark_2' => '#151021',
                'gray' => '#6b7280', 'gray_2' => '#a1a1aa', 'bg' => '#09090f', 'surface' => '#16151f',
                'surface_2' => '#211f2e', 'surface_3' => '#302a47', 'border' => '#3b3657', 'text' => '#f5f3ff',
                'muted' => '#b9b4d6', 'font' => 'Inter', 'radius' => '18px',
            ],
            'emerald-charcoal' => [
                'label' => 'Emerald Charcoal', 'description' => 'Emerald and mint tones on charcoal with balanced grey.',
                'primary' => '#4ade80', 'primary_2' => '#22c55e', 'secondary' => '#10b981', 'secondary_2' => '#059669',
                'accent' => '#a7f3d0', 'accent_2' => '#2dd4bf', 'dark' => '#04110a', 'dark_2' => '#071a10',
                'gray' => '#64748b', 'gray_2' => '#9ca3af', 'bg' => '#07140d', 'surface' => '#101d15',
                'surface_2' => '#18291f', 'surface_3' => '#223a2b', 'border' => '#2d513a', 'text' => '#f0fdf4',
                'muted' => '#afcfba', 'font' => 'Inter', 'radius' => '14px',
            ],
            'amber-carbon' => [
                'label' => 'Amber Carbon', 'description' => 'Golden amber, warm orange, carbon black and stone grey.',
                'primary' => '#fbbf24', 'primary_2' => '#f59e0b', 'secondary' => '#fb923c', 'secondary_2' => '#ea580c',
                'accent' => '#fde68a', 'accent_2' => '#f97316', 'dark' => '#140d05', 'dark_2' => '#1f160a',
                'gray' => '#78716c', 'gray_2' => '#a8a29e', 'bg' => '#171008', 'surface' => '#21170d',
                'surface_2' => '#312313', 'surface_3' => '#46321b', 'border' => '#5a3f1b', 'text' => '#fff7ed',
                'muted' => '#dbc2a0', 'font' => 'Inter', 'radius' => '12px',
            ],
            'rose-onyx' => [
                'label' => 'Rose Onyx', 'description' => 'Rose, pink, red warmth, onyx base and soft mauve grey.',
                'primary' => '#fb7185', 'primary_2' => '#f43f5e', 'secondary' => '#ec4899', 'secondary_2' => '#be185d',
                'accent' => '#f9a8d4', 'accent_2' => '#c084fc', 'dark' => '#16070d', 'dark_2' => '#22111a',
                'gray' => '#7c6771', 'gray_2' => '#d9a8bb', 'bg' => '#160910', 'surface' => '#22111a',
                'surface_2' => '#321827', 'surface_3' => '#472036', 'border' => '#5f2940', 'text' => '#fff1f2',
                'muted' => '#d9a8bb', 'font' => 'Poppins', 'radius' => '18px',
            ],
            'steel-sky' => [
                'label' => 'Steel Sky', 'description' => 'Steel blue, powder sky, slate dark and clean enterprise greys.',
                'primary' => '#60a5fa', 'primary_2' => '#3b82f6', 'secondary' => '#93c5fd', 'secondary_2' => '#2563eb',
                'accent' => '#bae6fd', 'accent_2' => '#38bdf8', 'dark' => '#0f172a', 'dark_2' => '#1e293b',
                'gray' => '#64748b', 'gray_2' => '#cbd5e1', 'bg' => '#0b1220', 'surface' => '#152033',
                'surface_2' => '#1e2c44', 'surface_3' => '#273a5a', 'border' => '#334a6f', 'text' => '#f8fafc',
                'muted' => '#bfd3ea', 'font' => 'Inter', 'radius' => '13px',
            ],
            'sandstorm-luxe' => [
                'label' => 'Sandstorm Luxe', 'description' => 'Warm beige, bronze, coffee dark and refined neutral greys.',
                'primary' => '#d6a85f', 'primary_2' => '#b7791f', 'secondary' => '#f5d08a', 'secondary_2' => '#92400e',
                'accent' => '#fef3c7', 'accent_2' => '#fb923c', 'dark' => '#120d08', 'dark_2' => '#24190f',
                'gray' => '#78716c', 'gray_2' => '#d6d3d1', 'bg' => '#17110a', 'surface' => '#241a10',
                'surface_2' => '#332515', 'surface_3' => '#4a351e', 'border' => '#6a4b26', 'text' => '#fff7ed',
                'muted' => '#dec9a4', 'font' => 'Inter', 'radius' => '14px',
            ],
            'mono-high-contrast' => [
                'label' => 'Mono High Contrast', 'description' => 'Accessible black, white, yellow signal and crisp neutral greys.',
                'primary' => '#ffff00', 'primary_2' => '#facc15', 'secondary' => '#ffffff', 'secondary_2' => '#e5e7eb',
                'accent' => '#22d3ee', 'accent_2' => '#84cc16', 'dark' => '#000000', 'dark_2' => '#111111',
                'gray' => '#737373', 'gray_2' => '#d4d4d4', 'bg' => '#000000', 'surface' => '#101010',
                'surface_2' => '#1b1b1b', 'surface_3' => '#262626', 'border' => '#ffffff', 'text' => '#ffffff',
                'muted' => '#e5e7eb', 'font' => 'Inter', 'radius' => '8px',
            ],
        ];
    }

    public static function presetOptions(): array
    {
        $options = [];
        foreach (self::presets() as $slug => $preset) {
            $options[$slug] = $preset['label'] ?? $slug;
        }
        return $options;
    }

    public static function preset(string $slug): ?array
    {
        return self::presets()[$slug] ?? null;
    }

    public static function defaultPresetSlug(): string
    {
        return 'ocean-midnight';
    }

    public static function filamentColors(?array $theme = null): array
    {
        $theme ??= self::preset(self::defaultPresetSlug()) ?? [];
        return [
            'primary' => $theme['primary'] ?? '#38bdf8',
            'gray' => $theme['gray'] ?? '#64748b',
            'success' => '#22c55e',
            'warning' => '#f59e0b',
            'danger' => '#ef4444',
            'info' => $theme['secondary'] ?? '#3b82f6',
        ];
    }

    public static function cssVariables(array $theme): string
    {
        $vars = [
            '--theme-primary' => $theme['primary'] ?? '#38bdf8',
            '--theme-primary-2' => $theme['primary_2'] ?? '#0ea5e9',
            '--theme-secondary' => $theme['secondary'] ?? '#2563eb',
            '--theme-secondary-2' => $theme['secondary_2'] ?? '#1d4ed8',
            '--theme-accent' => $theme['accent'] ?? '#22d3ee',
            '--theme-accent-2' => $theme['accent_2'] ?? '#14b8a6',
            '--theme-dark' => $theme['dark'] ?? '#020617',
            '--theme-dark-2' => $theme['dark_2'] ?? '#07111f',
            '--theme-gray' => $theme['gray'] ?? '#64748b',
            '--theme-gray-2' => $theme['gray_2'] ?? '#94a3b8',
            '--theme-bg' => $theme['bg'] ?? '#06111d',
            '--theme-surface' => $theme['surface'] ?? '#101b29',
            '--theme-surface-2' => $theme['surface_2'] ?? '#14263a',
            '--theme-surface-3' => $theme['surface_3'] ?? '#1c334d',
            '--theme-border' => $theme['border'] ?? '#24425f',
            '--theme-text' => $theme['text'] ?? '#eff6ff',
            '--theme-muted' => $theme['muted'] ?? '#a9c5df',
            '--theme-radius' => $theme['radius'] ?? '14px',
            '--theme-font' => $theme['font'] ?? 'Inter',
        ];
        return collect($vars)->map(fn ($v, $k) => $k . ': ' . $v . ';')->implode("\n");
    }
}
