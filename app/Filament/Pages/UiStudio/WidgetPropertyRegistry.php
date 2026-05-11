<?php

declare(strict_types=1);

namespace App\Filament\Pages\UiStudio;

/**
 * WidgetPropertyRegistry maps each studio widget type key to a flat list of
 * property field definitions.  The UiStudio page uses these definitions to
 * render an inline property editor in the Layout panel whenever a canvas card
 * is selected.
 *
 * Each field definition is an associative array with the following keys:
 *   - key       : string  — the property key stored in the widget's `properties` array
 *   - label     : string  — human-readable label shown in the editor
 *   - type      : string  — 'text' | 'textarea' | 'select' | 'toggle' | 'number'
 *   - default   : mixed   — value to pre-fill when no saved value exists
 *   - options   : array   — (select only) label-indexed option array
 *   - placeholder: string — (text/textarea only) placeholder hint
 *   - helper    : string  — (optional) short helper text shown below the field
 */
final class WidgetPropertyRegistry
{
    /** @return array<string, list<array<string, mixed>>> */
    public static function all(): array
    {
        return [
            'html-card' => [
                ['key' => 'title',   'label' => 'Title',   'type' => 'text',     'default' => '',    'placeholder' => 'Card title (optional)'],
                ['key' => 'content', 'label' => 'Content', 'type' => 'textarea', 'default' => '',    'placeholder' => 'HTML or plain text', 'rows' => 4],
            ],

            'stat-card' => [
                ['key' => 'title',       'label' => 'Title',       'type' => 'text',   'default' => '',          'placeholder' => 'e.g. Total Jobs'],
                ['key' => 'value',       'label' => 'Value',       'type' => 'text',   'default' => '',          'placeholder' => 'e.g. 124'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'text',   'default' => '',          'placeholder' => 'e.g. +12% from last month'],
                ['key' => 'icon',        'label' => 'Icon',        'type' => 'text',   'default' => 'heroicon-o-chart-bar', 'placeholder' => 'heroicon-o-briefcase'],
                [
                    'key'     => 'color',
                    'label'   => 'Color',
                    'type'    => 'select',
                    'default' => 'primary',
                    'options' => [
                        'gray'    => 'Gray',
                        'primary' => 'Primary',
                        'info'    => 'Info',
                        'success' => 'Success',
                        'warning' => 'Warning',
                        'danger'  => 'Danger',
                    ],
                ],
            ],

            'kpi-grid-card' => [
                ['key' => 'title',   'label' => 'Card Title (optional)', 'type' => 'text',     'default' => '',    'placeholder' => 'Key Performance Indicators'],
                [
                    'key'         => 'kpis',
                    'label'       => 'KPI Items (JSON)',
                    'type'        => 'textarea',
                    'default'     => '',
                    'placeholder' => '[{"label":"Total Jobs","value":"124","icon":"heroicon-o-briefcase","color":"primary"}]',
                    'rows'        => 5,
                    'helper'      => 'Each item: {"label":"…","value":"…","icon":"…","color":"primary|success|warning|danger|info|gray"}',
                ],
                [
                    'key'     => 'columns',
                    'label'   => 'Grid Columns',
                    'type'    => 'select',
                    'default' => '2',
                    'options' => ['2' => '2 columns', '3' => '3 columns', '4' => '4 columns'],
                ],
            ],

            'alert-notice-card' => [
                ['key' => 'title',   'label' => 'Alert Title', 'type' => 'text',     'default' => '',     'placeholder' => 'Important Notice'],
                ['key' => 'message', 'label' => 'Message',     'type' => 'textarea', 'default' => '',     'placeholder' => 'Alert body text here', 'rows' => 3],
                [
                    'key'     => 'type',
                    'label'   => 'Alert Type',
                    'type'    => 'select',
                    'default' => 'info',
                    'options' => [
                        'info'    => 'ℹ Info (Blue)',
                        'success' => '✓ Success (Green)',
                        'warning' => '⚠ Warning (Yellow)',
                        'danger'  => '✕ Danger (Red)',
                    ],
                ],
                ['key' => 'show_icon', 'label' => 'Show Icon', 'type' => 'toggle', 'default' => true],
            ],

            'recent-activity-card' => [
                ['key' => 'title',      'label' => 'Title',                 'type' => 'text',     'default' => 'Recent Activity', 'placeholder' => 'Recent Activity'],
                [
                    'key'         => 'items',
                    'label'       => 'Activity Items (JSON)',
                    'type'        => 'textarea',
                    'default'     => '',
                    'placeholder' => '[{"icon":"heroicon-o-user","label":"Alice created a job","time":"5m ago","color":"success"}]',
                    'rows'        => 5,
                    'helper'      => 'Each item: {"icon":"…","label":"…","time":"…","color":"success|warning|danger|info|gray"}',
                ],
                ['key' => 'max_items',   'label' => 'Max Items',        'type' => 'number',   'default' => 5, 'placeholder' => '5'],
                [
                    'key'     => 'empty_state',
                    'label'   => 'Empty State Text',
                    'type'    => 'select',
                    'default' => 'No recent activity.',
                    'options' => [
                        'No recent activity.'  => 'No recent activity.',
                        'Nothing to show yet.' => 'Nothing to show yet.',
                        'All quiet here.'      => 'All quiet here.',
                    ],
                ],
            ],

            'cta-button-card' => [
                ['key' => 'heading',     'label' => 'Heading',             'type' => 'text',     'default' => '',             'placeholder' => 'Take action now'],
                ['key' => 'description', 'label' => 'Description',         'type' => 'textarea', 'default' => '',             'placeholder' => 'Brief supporting text', 'rows' => 2],
                ['key' => 'button_text', 'label' => 'Button Label',        'type' => 'text',     'default' => 'Get Started',  'placeholder' => 'Get Started'],
                ['key' => 'button_url',  'label' => 'Button URL',          'type' => 'text',     'default' => '#',            'placeholder' => '/admin/jobs/create'],
                ['key' => 'open_new_tab','label' => 'Open in New Tab',     'type' => 'toggle',   'default' => false],
                [
                    'key'     => 'button_color',
                    'label'   => 'Button Color',
                    'type'    => 'select',
                    'default' => 'primary',
                    'options' => ['primary' => 'Primary', 'success' => 'Success', 'warning' => 'Warning', 'danger' => 'Danger'],
                ],
                [
                    'key'     => 'style',
                    'label'   => 'Card Style',
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => ['default' => 'Default', 'centered' => 'Centered / Highlighted'],
                ],
            ],

            'chart-bar-card' => [
                ['key' => 'title',       'label' => 'Chart Title',   'type' => 'text',     'default' => '',  'placeholder' => 'e.g. Revenue Over Time'],
                ['key' => 'description', 'label' => 'Description',   'type' => 'text',     'default' => '',  'placeholder' => 'What data will this chart show?'],
                [
                    'key'     => 'height',
                    'label'   => 'Card Height',
                    'type'    => 'select',
                    'default' => 'md',
                    'options' => ['sm' => 'Small (200px)', 'md' => 'Medium (300px)', 'lg' => 'Large (400px)'],
                ],
            ],

            'chart-line-card' => [
                ['key' => 'title',       'label' => 'Chart Title',   'type' => 'text',     'default' => '',  'placeholder' => 'e.g. Bookings Over Time'],
                ['key' => 'description', 'label' => 'Description',   'type' => 'text',     'default' => '',  'placeholder' => 'What data will this chart show?'],
                [
                    'key'     => 'height',
                    'label'   => 'Card Height',
                    'type'    => 'select',
                    'default' => 'md',
                    'options' => ['sm' => 'Small (200px)', 'md' => 'Medium (300px)', 'lg' => 'Large (400px)'],
                ],
            ],

            'table-card' => [
                ['key' => 'title',         'label' => 'Table Title',                     'type' => 'text',     'default' => '',                  'placeholder' => 'e.g. Recent Jobs'],
                ['key' => 'headers',       'label' => 'Column Headers (comma-separated)', 'type' => 'text',     'default' => '',                  'placeholder' => 'Name, Status, Date'],
                [
                    'key'         => 'rows',
                    'label'       => 'Rows (JSON array of arrays)',
                    'type'        => 'textarea',
                    'default'     => '',
                    'placeholder' => '[["Job #1","Active","Today"],["Job #2","Done","Yesterday"]]',
                    'rows'        => 4,
                    'helper'      => 'Each inner array is one row. Columns must match header count.',
                ],
                ['key' => 'empty_message', 'label' => 'Empty State Message',             'type' => 'text',     'default' => 'No data available.', 'placeholder' => 'No data available.'],
            ],

            'map-card' => [
                ['key' => 'title',       'label' => 'Card Title',    'type' => 'text',   'default' => 'Live Map',  'placeholder' => 'Live Map'],
                ['key' => 'refresh_interval', 'label' => 'Refresh Interval (seconds)', 'type' => 'number', 'default' => 30, 'placeholder' => '30'],
            ],
        ];
    }

    /**
     * Return the field schema for the given widget type, or an empty array if
     * the type has no registered properties.
     *
     * @return list<array<string, mixed>>
     */
    public static function schema(string $widgetType): array
    {
        return self::all()[$widgetType] ?? [];
    }

    /**
     * Return the default property values for the given widget type as a flat
     * key → value map, suitable for initialising a new canvas widget.
     *
     * @return array<string, mixed>
     */
    public static function defaults(string $widgetType): array
    {
        $defaults = [];
        foreach (self::schema($widgetType) as $field) {
            $defaults[$field['key']] = $field['default'] ?? '';
        }

        return $defaults;
    }
}
