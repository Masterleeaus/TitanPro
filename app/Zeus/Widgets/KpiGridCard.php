<?php

declare(strict_types=1);

namespace App\Zeus\Widgets;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;
use LaraZeus\DynamicDashboard\Contracts\Widget;

/**
 * A KPI grid card that shows multiple key performance indicators in a grid layout.
 *
 * KPI data is entered as JSON:
 * [{"label":"Total Jobs","value":"124","icon":"heroicon-o-briefcase","color":"primary"},...]
 */
class KpiGridCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('kpi-grid-card')
            ->label(__('KPI Grid Card'))
            ->icon('heroicon-o-squares-2x2')
            ->schema([
                TextInput::make('title')
                    ->label(__('Card Title (optional)'))
                    ->placeholder(__('Key Performance Indicators')),

                Textarea::make('kpis')
                    ->label(__('KPI Items (JSON)'))
                    ->placeholder(__('[{"label":"Total Jobs","value":"124","icon":"heroicon-o-briefcase","color":"primary"},{"label":"Revenue","value":"$8,450","icon":"heroicon-o-currency-dollar","color":"success"}]'))
                    ->rows(6)
                    ->required()
                    ->helperText(__('Each item: {"label":"...", "value":"...", "icon":"...", "color":"primary|success|warning|danger|info|gray"}')),

                Select::make('columns')
                    ->label(__('Grid Columns'))
                    ->options([
                        '2' => '2 columns',
                        '3' => '3 columns',
                        '4' => '4 columns',
                    ])
                    ->default('2'),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title   = e($data['title'] ?? '');
        $kpisRaw = $data['kpis'] ?? '[]';
        $columns = (int) ($data['columns'] ?? 2);

        $kpis = [];
        if (is_string($kpisRaw) && $kpisRaw !== '') {
            $decoded = json_decode($kpisRaw, true);
            $kpis    = is_array($decoded) ? $decoded : [];
        }

        if (empty($kpis)) {
            return '<div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm text-sm text-gray-400 dark:text-gray-500">No KPI data provided.</div>';
        }

        $colClass = match ($columns) {
            3 => 'grid-cols-3',
            4 => 'grid-cols-4',
            default => 'grid-cols-2',
        };

        $colorMap = [
            'primary' => 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30',
            'success' => 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30',
            'warning' => 'text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/30',
            'danger'  => 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30',
            'info'    => 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30',
            'gray'    => 'text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50',
        ];

        $cells = '';
        foreach ($kpis as $kpi) {
            if (! is_array($kpi)) {
                continue;
            }
            $label  = e($kpi['label'] ?? '');
            $value  = e($kpi['value'] ?? '');
            $icon   = e($kpi['icon'] ?? 'heroicon-o-chart-bar');
            $color  = $kpi['color'] ?? 'primary';
            $cc     = $colorMap[$color] ?? $colorMap['primary'];

            $cells .= <<<CELL
<div class="flex flex-col rounded-lg border border-gray-100 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-700/30">
    <div class="flex items-center gap-2 mb-1">
        <span class="rounded-md p-1 {$cc}">
            <x-icon name="{$icon}" class="h-4 w-4" />
        </span>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">{$label}</p>
    </div>
    <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{$value}</p>
</div>
CELL;
        }

        $titleHtml = $title !== ''
            ? '<h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">' . $title . '</h3>'
            : '';

        return <<<HTML
<div class="fi-kpi-grid-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
    {$titleHtml}
    <div class="grid {$colClass} gap-3">{$cells}</div>
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }
}
