<?php

declare(strict_types=1);

namespace App\Zeus\Widgets;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;
use LaraZeus\DynamicDashboard\Contracts\Widget;

/**
 * A stats/counter card for Dynamic Dashboard layouts.
 * Displays a key metric with an optional trend indicator.
 */
class StatsCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('stats-card')
            ->label(__('Stats Card'))
            ->icon('heroicon-o-chart-bar')
            ->schema([
                TextInput::make('title')
                    ->label(__('Title'))
                    ->placeholder(__('e.g. Total Jobs'))
                    ->required(),

                TextInput::make('value')
                    ->label(__('Value'))
                    ->placeholder(__('e.g. 124'))
                    ->required(),

                TextInput::make('description')
                    ->label(__('Description / Sub-text'))
                    ->placeholder(__('e.g. +12% from last month')),

                TextInput::make('icon')
                    ->label(__('Heroicon Name'))
                    ->placeholder(__('heroicon-o-briefcase')),

                Select::make('color')
                    ->label(__('Color'))
                    ->options([
                        'gray'    => 'Gray',
                        'primary' => 'Primary',
                        'info'    => 'Info',
                        'success' => 'Success',
                        'warning' => 'Warning',
                        'danger'  => 'Danger',
                    ])
                    ->default('primary'),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title       = e($data['title'] ?? '');
        $value       = e($data['value'] ?? '—');
        $description = e($data['description'] ?? '');
        $icon        = e($data['icon'] ?? 'heroicon-o-chart-bar');
        $color       = $data['color'] ?? 'primary';

        $colorMap = [
            'gray'    => 'text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700',
            'primary' => 'text-primary-600 dark:text-primary-400 bg-primary-100 dark:bg-primary-900',
            'info'    => 'text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900',
            'success' => 'text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900',
            'warning' => 'text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900',
            'danger'  => 'text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900',
        ];

        $colorClass = $colorMap[$color] ?? $colorMap['primary'];

        return <<<HTML
<div class="fi-stats-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{$title}</p>
            <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100">{$value}</p>
            {$this->descriptionHtml($description)}
        </div>
        <div class="rounded-full p-3 {$colorClass}">
            <x-icon name="{$icon}" class="h-6 w-6" />
        </div>
    </div>
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }

    private function descriptionHtml(string $description): string
    {
        if ($description === '') {
            return '';
        }

        return '<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">' . $description . '</p>';
    }
}
