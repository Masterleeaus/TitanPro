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
 * A prominent call-to-action card with heading, description, and button.
 */
class CtaButtonCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('cta-button-card')
            ->label(__('CTA Button Card'))
            ->icon('heroicon-o-cursor-arrow-ripple')
            ->schema([
                TextInput::make('heading')
                    ->label(__('Heading'))
                    ->placeholder(__('Take action now'))
                    ->required(),

                Textarea::make('description')
                    ->label(__('Description (optional)'))
                    ->placeholder(__('Brief supporting text'))
                    ->rows(2),

                TextInput::make('button_text')
                    ->label(__('Button Label'))
                    ->placeholder(__('Get Started'))
                    ->required()
                    ->default('Get Started'),

                TextInput::make('button_url')
                    ->label(__('Button URL'))
                    ->placeholder(__('/admin/jobs/create or https://example.com'))
                    ->required(),

                Toggle::make('open_new_tab')
                    ->label(__('Open in new tab'))
                    ->default(false),

                Select::make('button_color')
                    ->label(__('Button Color'))
                    ->options([
                        'primary' => 'Primary',
                        'success' => 'Success',
                        'warning' => 'Warning',
                        'danger'  => 'Danger',
                    ])
                    ->default('primary'),

                Select::make('style')
                    ->label(__('Card Style'))
                    ->options([
                        'default'  => 'Default',
                        'centered' => 'Centered / Highlighted',
                    ])
                    ->default('default'),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $heading     = e($data['heading'] ?? '');
        $description = e($data['description'] ?? '');
        $buttonText  = e($data['button_text'] ?? 'Get Started');
        $buttonUrl   = e($data['button_url'] ?? '#');
        $openNewTab  = (bool) ($data['open_new_tab'] ?? false);
        $buttonColor = $data['button_color'] ?? 'primary';
        $style       = $data['style'] ?? 'default';

        $target = $openNewTab ? 'target="_blank" rel="noopener noreferrer"' : '';

        $colorMap = [
            'primary' => 'bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 text-white',
            'success' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white',
            'warning' => 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-400 text-white',
            'danger'  => 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white',
        ];

        $btnClass = $colorMap[$buttonColor] ?? $colorMap['primary'];

        $descHtml = $description !== ''
            ? '<p class="mt-2 text-sm text-gray-500 dark:text-gray-400">' . $description . '</p>'
            : '';

        $alignment = $style === 'centered' ? 'text-center items-center' : '';
        $cardBg    = $style === 'centered'
            ? 'bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 border-primary-200 dark:border-primary-800'
            : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700';

        return <<<HTML
<div class="fi-cta-card rounded-xl border {$cardBg} p-6 shadow-sm">
    <div class="flex flex-col {$alignment}">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{$heading}</h3>
        {$descHtml}
        <div class="mt-4">
            <a href="{$buttonUrl}" {$target}
               class="inline-flex items-center rounded-lg px-5 py-2.5 text-sm font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 {$btnClass}">
                {$buttonText}
            </a>
        </div>
    </div>
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }
}
