<?php

declare(strict_types=1);

namespace App\Zeus\Widgets;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Contracts\View\View;
use LaraZeus\DynamicDashboard\Contracts\Widget;

/**
 * A card with a link / call-to-navigate button.
 */
class LinkCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('link-card')
            ->label(__('Link Card'))
            ->icon('heroicon-o-link')
            ->schema([
                TextInput::make('title')
                    ->label(__('Card Title'))
                    ->placeholder(__('e.g. View Reports'))
                    ->required(),

                Textarea::make('description')
                    ->label(__('Description'))
                    ->placeholder(__('Short text explaining the link'))
                    ->rows(2),

                TextInput::make('link_text')
                    ->label(__('Link / Button Text'))
                    ->placeholder(__('e.g. Open →'))
                    ->default('Open'),

                TextInput::make('link_url')
                    ->label(__('URL'))
                    ->placeholder(__('https://example.com or /admin/reports'))
                    ->required(),

                Toggle::make('new_tab')
                    ->label(__('Open in new tab'))
                    ->default(false),

                Select::make('color')
                    ->label(__('Button Color'))
                    ->options([
                        'primary' => 'Primary',
                        'success' => 'Success',
                        'warning' => 'Warning',
                        'danger'  => 'Danger',
                        'gray'    => 'Gray',
                    ])
                    ->default('primary'),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title       = e($data['title'] ?? '');
        $description = e($data['description'] ?? '');
        $linkText    = e($data['link_text'] ?? 'Open');
        $linkUrl     = e($data['link_url'] ?? '#');
        $newTab      = (bool) ($data['new_tab'] ?? false);
        $color       = $data['color'] ?? 'primary';

        $target = $newTab ? 'target="_blank" rel="noopener noreferrer"' : '';

        $colorMap = [
            'primary' => 'bg-primary-600 hover:bg-primary-700 text-white',
            'success' => 'bg-green-600 hover:bg-green-700 text-white',
            'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white',
            'danger'  => 'bg-red-600 hover:bg-red-700 text-white',
            'gray'    => 'bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100',
        ];

        $btnClass = $colorMap[$color] ?? $colorMap['primary'];

        $descHtml = $description !== ''
            ? '<p class="text-sm text-gray-500 dark:text-gray-400 mb-4">' . $description . '</p>'
            : '';

        return <<<HTML
<div class="fi-link-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2">{$title}</h3>
    {$descHtml}
    <a href="{$linkUrl}" {$target} class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium transition-colors {$btnClass}">
        {$linkText}
    </a>
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }
}
