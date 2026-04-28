<?php

declare(strict_types=1);

namespace App\Zeus\Widgets;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;
use LaraZeus\DynamicDashboard\Contracts\Widget;

/**
 * A placeholder card for future chart integrations.
 * Displays the chart type label and a descriptive placeholder graphic.
 */
class ChartPlaceholderCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('chart-placeholder-card')
            ->label(__('Chart Placeholder'))
            ->icon('heroicon-o-chart-pie')
            ->schema([
                TextInput::make('title')
                    ->label(__('Chart Title'))
                    ->placeholder(__('e.g. Revenue Over Time'))
                    ->required(),

                TextInput::make('description')
                    ->label(__('Description'))
                    ->placeholder(__('What data will this chart show?')),

                Select::make('chart_type')
                    ->label(__('Chart Type'))
                    ->options([
                        'bar'    => 'Bar Chart',
                        'line'   => 'Line Chart',
                        'area'   => 'Area Chart',
                        'pie'    => 'Pie Chart',
                        'donut'  => 'Donut Chart',
                        'radar'  => 'Radar Chart',
                    ])
                    ->default('bar'),

                Select::make('height')
                    ->label(__('Card Height'))
                    ->options([
                        'sm' => 'Small (200px)',
                        'md' => 'Medium (300px)',
                        'lg' => 'Large (400px)',
                    ])
                    ->default('md'),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title       = e($data['title'] ?? '');
        $description = e($data['description'] ?? '');
        $chartType   = e($data['chart_type'] ?? 'bar');
        $height      = $data['height'] ?? 'md';

        $heightMap = [
            'sm' => '200px',
            'md' => '300px',
            'lg' => '400px',
        ];

        $heightPx = $heightMap[$height] ?? '300px';

        $chartIcons = [
            'bar'   => 'heroicon-o-chart-bar',
            'line'  => 'heroicon-o-chart-bar',
            'area'  => 'heroicon-o-chart-bar',
            'pie'   => 'heroicon-o-chart-pie',
            'donut' => 'heroicon-o-chart-pie',
            'radar' => 'heroicon-o-chart-bar',
        ];

        $icon = $chartIcons[$chartType] ?? 'heroicon-o-chart-bar';

        $descHtml = $description !== ''
            ? '<p class="mt-1 text-sm text-gray-500 dark:text-gray-400">' . $description . '</p>'
            : '';

        return <<<HTML
<div class="fi-chart-placeholder-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{$title}</h3>
            {$descHtml}
        </div>
        <span class="rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-1 text-xs text-gray-500 dark:text-gray-400 capitalize">{$chartType}</span>
    </div>
    <div style="height:{$heightPx}" class="flex flex-col items-center justify-center rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-dashed border-gray-300 dark:border-gray-600">
        <x-icon name="{$icon}" class="h-12 w-12 text-gray-300 dark:text-gray-500" />
        <p class="mt-2 text-sm text-gray-400 dark:text-gray-500">Chart coming soon</p>
        <p class="text-xs text-gray-300 dark:text-gray-600 capitalize">{$chartType} chart</p>
    </div>
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }
}
