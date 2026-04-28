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
 * A card with a prominent icon, title, and short description.
 */
class IconCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('icon-card')
            ->label(__('Icon Card'))
            ->icon('heroicon-o-star')
            ->schema([
                TextInput::make('title')
                    ->label(__('Title'))
                    ->placeholder(__('Feature or section name'))
                    ->required(),

                Textarea::make('content')
                    ->label(__('Description'))
                    ->placeholder(__('Short description text'))
                    ->rows(2),

                TextInput::make('icon')
                    ->label(__('Heroicon Name'))
                    ->placeholder(__('heroicon-o-users'))
                    ->default('heroicon-o-star'),

                Select::make('color')
                    ->label(__('Icon Color'))
                    ->options([
                        'primary' => 'Primary',
                        'success' => 'Success',
                        'warning' => 'Warning',
                        'danger'  => 'Danger',
                        'info'    => 'Info',
                        'gray'    => 'Gray',
                    ])
                    ->default('primary'),

                Select::make('layout')
                    ->label(__('Layout'))
                    ->options([
                        'horizontal' => 'Horizontal (icon left)',
                        'vertical'   => 'Vertical (icon top)',
                    ])
                    ->default('horizontal'),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title   = e($data['title'] ?? '');
        $content = e($data['content'] ?? '');
        $icon    = e($data['icon'] ?? 'heroicon-o-star');
        $color   = $data['color'] ?? 'primary';
        $layout  = $data['layout'] ?? 'horizontal';

        $colorClasses = [
            'primary' => 'text-primary-600 dark:text-primary-400 bg-primary-100 dark:bg-primary-900/40',
            'success' => 'text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/40',
            'warning' => 'text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/40',
            'danger'  => 'text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/40',
            'info'    => 'text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/40',
            'gray'    => 'text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700',
        ];

        $cc = $colorClasses[$color] ?? $colorClasses['primary'];

        $contentHtml = $content !== ''
            ? '<p class="text-sm text-gray-500 dark:text-gray-400">' . $content . '</p>'
            : '';

        if ($layout === 'vertical') {
            return <<<HTML
<div class="fi-icon-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm text-center">
    <div class="flex justify-center mb-3">
        <div class="rounded-full p-3 {$cc}">
            <x-icon name="{$icon}" class="h-8 w-8" />
        </div>
    </div>
    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">{$title}</h3>
    {$contentHtml}
</div>
HTML;
        }

        return <<<HTML
<div class="fi-icon-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm flex items-start gap-4">
    <div class="rounded-full p-3 {$cc} flex-shrink-0">
        <x-icon name="{$icon}" class="h-6 w-6" />
    </div>
    <div>
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">{$title}</h3>
        {$contentHtml}
    </div>
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }
}
