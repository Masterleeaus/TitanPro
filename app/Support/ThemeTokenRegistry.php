<?php

declare(strict_types=1);

namespace App\Support;

final class ThemeTokenRegistry
{
    public static function taxonomy(): array
    {
        return [
            'primitive' => [
                '--color-blue-600' => ['value' => '#2563eb', 'type' => 'color', 'label' => 'Blue 600'],
                '--color-slate-900' => ['value' => '#0f172a', 'type' => 'color', 'label' => 'Slate 900'],
                '--color-slate-50' => ['value' => '#f8fafc', 'type' => 'color', 'label' => 'Slate 50'],
                '--color-teal-500' => ['value' => '#14b8a6', 'type' => 'color', 'label' => 'Teal 500'],
                '--color-white' => ['value' => '#ffffff', 'type' => 'color', 'label' => 'White'],
                '--radius-md' => ['value' => '8px', 'type' => 'dimension', 'label' => 'Medium radius'],
                '--shadow-card' => ['value' => '0 1px 3px rgba(0, 0, 0, 0.12)', 'type' => 'shadow', 'label' => 'Card shadow'],
                '--font-sans-default' => ['value' => 'Figtree', 'type' => 'font', 'label' => 'Default sans font'],
            ],
            'semantic' => [
                '--color-primary' => ['value' => 'var(--color-blue-600)', 'type' => 'color', 'label' => 'Primary'],
                '--color-secondary' => ['value' => 'var(--color-slate-900)', 'type' => 'color', 'label' => 'Secondary'],
                '--color-accent' => ['value' => 'var(--color-teal-500)', 'type' => 'color', 'label' => 'Accent'],
                '--color-surface' => ['value' => 'var(--color-slate-50)', 'type' => 'color', 'label' => 'Surface'],
                '--font-heading' => ['value' => 'var(--font-sans-default)', 'type' => 'font', 'label' => 'Heading font'],
                '--font-body' => ['value' => 'var(--font-sans-default)', 'type' => 'font', 'label' => 'Body font'],
                '--bg-image' => ['value' => 'none', 'type' => 'background', 'label' => 'Background image'],
            ],
            'component' => [
                '--app-bg' => ['value' => 'var(--color-surface)', 'type' => 'color', 'label' => 'App background'],
                '--app-heading-font' => ['value' => 'var(--font-heading)', 'type' => 'font', 'label' => 'App heading font'],
                '--app-body-font' => ['value' => 'var(--font-body)', 'type' => 'font', 'label' => 'App body font'],
                '--sidebar-bg' => ['value' => 'var(--color-secondary)', 'type' => 'color', 'label' => 'Sidebar background'],
                '--sidebar-fg' => ['value' => 'var(--color-white)', 'type' => 'color', 'label' => 'Sidebar foreground'],
                '--card-bg' => ['value' => 'var(--color-surface)', 'type' => 'color', 'label' => 'Card background'],
                '--card-radius' => ['value' => 'var(--radius-md)', 'type' => 'dimension', 'label' => 'Card radius'],
                '--card-shadow' => ['value' => 'var(--shadow-card)', 'type' => 'shadow', 'label' => 'Card shadow'],
                '--btn-primary-bg' => ['value' => 'var(--color-primary)', 'type' => 'color', 'label' => 'Primary button background'],
                '--btn-primary-fg' => ['value' => 'var(--color-white)', 'type' => 'color', 'label' => 'Primary button foreground'],
                '--panel-accent' => ['value' => 'var(--color-accent)', 'type' => 'color', 'label' => 'Panel accent'],
            ],
        ];
    }

    public static function defaults(): array
    {
        return array_map(
            static fn (array $tokens): array => array_map(
                static fn (array $definition): string => $definition['value'],
                $tokens,
            ),
            self::taxonomy(),
        );
    }

    public static function editorFields(): array
    {
        return [
            'primary_color' => ['key' => '--color-primary', 'label' => 'Primary token', 'type' => 'color'],
            'secondary_color' => ['key' => '--color-secondary', 'label' => 'Secondary token', 'type' => 'color'],
            'accent_color' => ['key' => '--color-accent', 'label' => 'Accent token', 'type' => 'color'],
            'surface_color' => ['key' => '--color-surface', 'label' => 'Surface token', 'type' => 'color'],
            'font_heading' => ['key' => '--font-heading', 'label' => 'Heading font token', 'type' => 'font'],
            'font_body' => ['key' => '--font-body', 'label' => 'Body font token', 'type' => 'font'],
        ];
    }

    public static function defaultRows(): array
    {
        $rows = [];

        foreach (self::defaults() as $scope => $tokens) {
            foreach ($tokens as $key => $value) {
                $rows[] = [
                    'panel' => 'global',
                    'scope' => $scope,
                    'key' => $key,
                    'value' => $value,
                ];
            }
        }

        return $rows;
    }
}
