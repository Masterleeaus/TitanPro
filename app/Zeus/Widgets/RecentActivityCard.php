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
 * Recent activity feed card.
 *
 * Items are entered as JSON in the format:
 * [{"icon":"heroicon-o-user","label":"Alice created a job","time":"5m ago","color":"success"}]
 */
class RecentActivityCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('recent-activity-card')
            ->label(__('Recent Activity Card'))
            ->icon('heroicon-o-clock')
            ->schema([
                TextInput::make('title')
                    ->label(__('Title'))
                    ->placeholder(__('Recent Activity'))
                    ->default(__('Recent Activity')),

                Textarea::make('items')
                    ->label(__('Activity Items (JSON)'))
                    ->placeholder(__('[{"icon":"heroicon-o-user","label":"Alice created a job","time":"5m ago","color":"success"}]'))
                    ->rows(6)
                    ->helperText(__('Each item: {"icon":"...", "label":"...", "time":"...", "color":"success|warning|danger|info|gray"}')),

                TextInput::make('max_items')
                    ->label(__('Max Items to Display'))
                    ->numeric()
                    ->default(5)
                    ->minValue(1)
                    ->maxValue(20),

                Select::make('empty_state')
                    ->label(__('Empty State Text'))
                    ->options([
                        'No recent activity.' => 'No recent activity.',
                        'Nothing to show yet.' => 'Nothing to show yet.',
                        'All quiet here.' => 'All quiet here.',
                    ])
                    ->default('No recent activity.')
                    ->searchable(),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title       = e($data['title'] ?? 'Recent Activity');
        $itemsRaw    = $data['items'] ?? '[]';
        $maxItems    = (int) ($data['max_items'] ?? 5);
        $emptyState  = e($data['empty_state'] ?? 'No recent activity.');

        $items = [];
        if (is_string($itemsRaw) && $itemsRaw !== '') {
            $decoded = json_decode($itemsRaw, true);
            $items   = is_array($decoded) ? array_slice($decoded, 0, $maxItems) : [];
        }

        if (empty($items)) {
            return <<<HTML
<div class="fi-activity-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">{$title}</h3>
    <p class="text-sm text-gray-400 dark:text-gray-500">{$emptyState}</p>
</div>
HTML;
        }

        $colorDotMap = [
            'success' => 'bg-green-500',
            'warning' => 'bg-yellow-500',
            'danger'  => 'bg-red-500',
            'info'    => 'bg-blue-500',
            'gray'    => 'bg-gray-400',
            'primary' => 'bg-primary-500',
        ];

        $rows = '';
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }
            $label  = e($item['label'] ?? '');
            $time   = e($item['time'] ?? '');
            $color  = $item['color'] ?? 'gray';
            $dotCls = $colorDotMap[$color] ?? $colorDotMap['gray'];
            $rows .= <<<ROW
<li class="flex items-start gap-3 py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
    <span class="mt-1.5 h-2 w-2 flex-shrink-0 rounded-full {$dotCls}"></span>
    <div class="flex-1 min-w-0">
        <p class="text-sm text-gray-700 dark:text-gray-300 truncate">{$label}</p>
    </div>
    <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">{$time}</span>
</li>
ROW;
        }

        return <<<HTML
<div class="fi-activity-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{$title}</h3>
    </div>
    <ul class="px-5">{$rows}</ul>
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }
}
