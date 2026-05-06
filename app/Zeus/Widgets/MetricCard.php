<?php

declare(strict_types=1);

namespace App\Zeus\Widgets;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;
use LaraZeus\DynamicDashboard\Contracts\Widget;

/**
 * A KPI metric card that shows a value with a comparison to the previous period.
 */
class MetricCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('metric-card')
            ->label(__('Metric Card'))
            ->icon('heroicon-o-arrow-trending-up')
            ->schema([
                TextInput::make('title')
                    ->label(__('Metric Title'))
                    ->placeholder(__('e.g. Monthly Revenue'))
                    ->required(),

                TextInput::make('value')
                    ->label(__('Current Value'))
                    ->placeholder(__('e.g. $8,450'))
                    ->required(),

                TextInput::make('previous_value')
                    ->label(__('Previous Period Value'))
                    ->placeholder(__('e.g. $7,200')),

                TextInput::make('unit')
                    ->label(__('Unit / Prefix'))
                    ->placeholder(__('e.g. $ or % or orders')),

                Select::make('trend')
                    ->label(__('Trend Direction'))
                    ->options([
                        'up'      => '↑ Up',
                        'down'    => '↓ Down',
                        'neutral' => '→ Neutral',
                    ])
                    ->default('neutral'),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title         = e($data['title'] ?? '');
        $value         = e($data['value'] ?? '—');
        $previousValue = e($data['previous_value'] ?? '');
        $trend         = $data['trend'] ?? 'neutral';

        $trendConfig = [
            'up'      => ['icon' => '↑', 'class' => 'text-green-600 dark:text-green-400', 'bg' => 'bg-green-50 dark:bg-green-900/20'],
            'down'    => ['icon' => '↓', 'class' => 'text-red-600 dark:text-red-400', 'bg' => 'bg-red-50 dark:bg-red-900/20'],
            'neutral' => ['icon' => '→', 'class' => 'text-gray-500 dark:text-gray-400', 'bg' => 'bg-gray-50 dark:bg-gray-800'],
        ];

        $tc = $trendConfig[$trend] ?? $trendConfig['neutral'];

        $prevHtml = $previousValue !== ''
            ? '<span class="text-xs text-gray-400 dark:text-gray-500 ml-1">vs ' . $previousValue . '</span>'
            : '';

        return <<<HTML
<div class="fi-metric-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{$title}</p>
    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">{$value}</p>
    <div class="mt-2 flex items-center gap-1">
        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {$tc['class']} {$tc['bg']}">
            {$tc['icon']}
        </span>
        {$prevHtml}
    </div>
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }
}
