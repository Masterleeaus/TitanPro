<?php

declare(strict_types=1);

namespace App\Platform\Ui;

/**
 * ComponentRegistry maps every registered Filament UI component key to:
 *  - a human-readable label
 *  - one or more CSS selectors that target it in the rendered panel
 *  - a list of editable design tokens (color, text, select)
 *
 * Consumers use this registry to build the token editor UI and to emit
 * the correct CSS variable / selector overrides when values are saved.
 */
final class ComponentRegistry
{
    /**
     * Return the full registry definition.
     *
     * Each entry:
     *   key   => string — stable component identifier (stored in DB)
     *   label => string — human label shown in the UI
     *   selector => string — CSS selector(s) targeting the component
     *   tokens => array   — ordered list of editable design tokens
     *
     * Token shape:
     *   key     => string — stored as token_key in titan_ui_component_overrides
     *   label   => string — human label
     *   type    => 'color' | 'text' | 'select' — input type
     *   default => string — fallback when no override is stored
     *   options => array  — only for 'select' type: [value => label]
     *
     * @return array<string, array{label: string, selector: string, tokens: array<int, array<string, mixed>>}>
     */
    public static function all(): array
    {
        return [
            'stat-card' => [
                'label'    => 'Stat Card',
                'selector' => '.fi-wi-stats-overview-stat, .fi-stat-card',
                'tokens'   => [
                    ['key' => 'background',    'label' => 'Background',    'type' => 'color',  'default' => '#ffffff'],
                    ['key' => 'text-color',    'label' => 'Text Color',    'type' => 'color',  'default' => '#111827'],
                    ['key' => 'label-color',   'label' => 'Label Color',   'type' => 'color',  'default' => '#6b7280'],
                    ['key' => 'border-color',  'label' => 'Border Color',  'type' => 'color',  'default' => '#e5e7eb'],
                    ['key' => 'border-radius', 'label' => 'Border Radius', 'type' => 'text',   'default' => '0.75rem'],
                    ['key' => 'shadow',        'label' => 'Shadow',        'type' => 'select', 'default' => 'sm', 'options' => [
                        'none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large',
                    ]],
                    ['key' => 'padding',       'label' => 'Padding',       'type' => 'text',   'default' => '1.5rem'],
                ],
            ],

            'table' => [
                'label'    => 'Table',
                'selector' => '.fi-ta-table, .fi-ta-content',
                'tokens'   => [
                    ['key' => 'header-background', 'label' => 'Header Background', 'type' => 'color',  'default' => '#f9fafb'],
                    ['key' => 'header-text',        'label' => 'Header Text',       'type' => 'color',  'default' => '#374151'],
                    ['key' => 'row-background',     'label' => 'Row Background',    'type' => 'color',  'default' => '#ffffff'],
                    ['key' => 'row-hover',          'label' => 'Row Hover',         'type' => 'color',  'default' => '#f9fafb'],
                    ['key' => 'border-color',       'label' => 'Border Color',      'type' => 'color',  'default' => '#e5e7eb'],
                    ['key' => 'border-radius',      'label' => 'Border Radius',     'type' => 'text',   'default' => '0.75rem'],
                    ['key' => 'font-size',          'label' => 'Font Size',         'type' => 'select', 'default' => 'sm', 'options' => [
                        'xs' => 'Extra Small', 'sm' => 'Small', 'base' => 'Base', 'lg' => 'Large',
                    ]],
                ],
            ],

            'modal' => [
                'label'    => 'Modal',
                'selector' => '.fi-modal-window, .fi-fo-section-content',
                'tokens'   => [
                    ['key' => 'background',    'label' => 'Background',    'type' => 'color',  'default' => '#ffffff'],
                    ['key' => 'overlay',       'label' => 'Overlay',       'type' => 'color',  'default' => 'rgba(0,0,0,0.5)'],
                    ['key' => 'border-radius', 'label' => 'Border Radius', 'type' => 'text',   'default' => '1rem'],
                    ['key' => 'shadow',        'label' => 'Shadow',        'type' => 'select', 'default' => 'xl', 'options' => [
                        'none' => 'None', 'md' => 'Medium', 'xl' => 'Extra Large', '2xl' => '2XL',
                    ]],
                    ['key' => 'padding',       'label' => 'Padding',       'type' => 'text',   'default' => '1.5rem'],
                    ['key' => 'header-color',  'label' => 'Header Text',   'type' => 'color',  'default' => '#111827'],
                ],
            ],

            'sidebar' => [
                'label'    => 'Sidebar',
                'selector' => '.fi-sidebar, .fi-sidebar-nav',
                'tokens'   => [
                    ['key' => 'background',       'label' => 'Background',       'type' => 'color', 'default' => '#ffffff'],
                    ['key' => 'text-color',        'label' => 'Text Color',       'type' => 'color', 'default' => '#374151'],
                    ['key' => 'active-background', 'label' => 'Active Item BG',   'type' => 'color', 'default' => '#eff6ff'],
                    ['key' => 'active-text',       'label' => 'Active Item Text', 'type' => 'color', 'default' => '#2563eb'],
                    ['key' => 'border-color',      'label' => 'Border Color',     'type' => 'color', 'default' => '#e5e7eb'],
                    ['key' => 'width',             'label' => 'Width',            'type' => 'text',  'default' => '16rem'],
                ],
            ],

            'nav-group' => [
                'label'    => 'Nav Group',
                'selector' => '.fi-sidebar-group',
                'tokens'   => [
                    ['key' => 'label-color',   'label' => 'Label Color',   'type' => 'color', 'default' => '#9ca3af'],
                    ['key' => 'item-color',    'label' => 'Item Color',    'type' => 'color', 'default' => '#374151'],
                    ['key' => 'item-hover',    'label' => 'Item Hover BG', 'type' => 'color', 'default' => '#f9fafb'],
                    ['key' => 'icon-color',    'label' => 'Icon Color',    'type' => 'color', 'default' => '#6b7280'],
                    ['key' => 'border-radius', 'label' => 'Item Radius',   'type' => 'text',  'default' => '0.5rem'],
                ],
            ],

            'widget-container' => [
                'label'    => 'Widget Container',
                'selector' => '.fi-wi, .fi-widgets-container',
                'tokens'   => [
                    ['key' => 'background',    'label' => 'Background',    'type' => 'color', 'default' => 'transparent'],
                    ['key' => 'gap',           'label' => 'Gap',           'type' => 'text',  'default' => '1.5rem'],
                    ['key' => 'padding',       'label' => 'Padding',       'type' => 'text',  'default' => '0'],
                    ['key' => 'border-radius', 'label' => 'Border Radius', 'type' => 'text',  'default' => '0'],
                ],
            ],

            'form-section' => [
                'label'    => 'Form Section',
                'selector' => '.fi-fo-section, .fi-fo-section-content-ctn',
                'tokens'   => [
                    ['key' => 'background',    'label' => 'Background',    'type' => 'color',  'default' => '#ffffff'],
                    ['key' => 'heading-color', 'label' => 'Heading Color', 'type' => 'color',  'default' => '#111827'],
                    ['key' => 'border-color',  'label' => 'Border Color',  'type' => 'color',  'default' => '#e5e7eb'],
                    ['key' => 'border-radius', 'label' => 'Border Radius', 'type' => 'text',   'default' => '0.75rem'],
                    ['key' => 'padding',       'label' => 'Padding',       'type' => 'text',   'default' => '1.5rem'],
                    ['key' => 'shadow',        'label' => 'Shadow',        'type' => 'select', 'default' => 'sm', 'options' => [
                        'none' => 'None', 'sm' => 'Small', 'md' => 'Medium',
                    ]],
                ],
            ],

            'hero-panel' => [
                'label'    => 'Hero Panel',
                'selector' => '.fi-hero-panel, .fi-page-header',
                'tokens'   => [
                    ['key' => 'background',    'label' => 'Background',    'type' => 'color', 'default' => '#2563eb'],
                    ['key' => 'text-color',    'label' => 'Text Color',    'type' => 'color', 'default' => '#ffffff'],
                    ['key' => 'padding',       'label' => 'Padding',       'type' => 'text',  'default' => '3rem 2rem'],
                    ['key' => 'border-radius', 'label' => 'Border Radius', 'type' => 'text',  'default' => '0'],
                ],
            ],

            'empty-state' => [
                'label'    => 'Empty State',
                'selector' => '.fi-ta-empty-state, .fi-fo-field-wrp-hint',
                'tokens'   => [
                    ['key' => 'icon-color',    'label' => 'Icon Color',    'type' => 'color', 'default' => '#d1d5db'],
                    ['key' => 'text-color',    'label' => 'Text Color',    'type' => 'color', 'default' => '#6b7280'],
                    ['key' => 'heading-color', 'label' => 'Heading Color', 'type' => 'color', 'default' => '#111827'],
                    ['key' => 'background',    'label' => 'Background',    'type' => 'color', 'default' => 'transparent'],
                    ['key' => 'padding',       'label' => 'Padding',       'type' => 'text',  'default' => '3rem'],
                ],
            ],
        ];
    }

    /**
     * Return a single component definition by key, or null if not found.
     *
     * @return array{label: string, selector: string, tokens: array<int, array<string, mixed>>}|null
     */
    public static function get(string $key): ?array
    {
        return static::all()[$key] ?? null;
    }

    /**
     * Return every token key for a given component.
     *
     * @return array<string>
     */
    public static function tokenKeys(string $componentKey): array
    {
        $component = static::get($componentKey);
        if ($component === null) {
            return [];
        }

        return array_column($component['tokens'], 'key');
    }

    /**
     * Return the default token values for a component, keyed by token key.
     *
     * @return array<string, string>
     */
    public static function defaults(string $componentKey): array
    {
        $component = static::get($componentKey);
        if ($component === null) {
            return [];
        }

        $defaults = [];
        foreach ($component['tokens'] as $token) {
            $defaults[$token['key']] = $token['default'];
        }

        return $defaults;
    }
}
