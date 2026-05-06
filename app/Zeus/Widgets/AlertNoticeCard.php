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
 * An alert / notice / announcement card for dashboards.
 */
class AlertNoticeCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('alert-notice-card')
            ->label(__('Alert / Notice Card'))
            ->icon('heroicon-o-bell-alert')
            ->schema([
                TextInput::make('title')
                    ->label(__('Alert Title'))
                    ->placeholder(__('Important Notice'))
                    ->required(),

                Textarea::make('message')
                    ->label(__('Message'))
                    ->placeholder(__('Alert body text here'))
                    ->rows(3)
                    ->required(),

                Select::make('type')
                    ->label(__('Alert Type'))
                    ->options([
                        'info'    => 'ℹ Info (Blue)',
                        'success' => '✓ Success (Green)',
                        'warning' => '⚠ Warning (Yellow)',
                        'danger'  => '✕ Danger / Error (Red)',
                    ])
                    ->default('info'),

                Toggle::make('show_icon')
                    ->label(__('Show Icon'))
                    ->default(true),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title    = e($data['title'] ?? '');
        $message  = e($data['message'] ?? '');
        $type     = $data['type'] ?? 'info';
        $showIcon = (bool) ($data['show_icon'] ?? true);

        $typeConfig = [
            'info' => [
                'bg'     => 'bg-blue-50 dark:bg-blue-900/20',
                'border' => 'border-blue-200 dark:border-blue-800',
                'title'  => 'text-blue-800 dark:text-blue-200',
                'msg'    => 'text-blue-700 dark:text-blue-300',
                'icon'   => 'heroicon-o-information-circle',
                'ic'     => 'text-blue-500',
            ],
            'success' => [
                'bg'     => 'bg-green-50 dark:bg-green-900/20',
                'border' => 'border-green-200 dark:border-green-800',
                'title'  => 'text-green-800 dark:text-green-200',
                'msg'    => 'text-green-700 dark:text-green-300',
                'icon'   => 'heroicon-o-check-circle',
                'ic'     => 'text-green-500',
            ],
            'warning' => [
                'bg'     => 'bg-yellow-50 dark:bg-yellow-900/20',
                'border' => 'border-yellow-200 dark:border-yellow-800',
                'title'  => 'text-yellow-800 dark:text-yellow-200',
                'msg'    => 'text-yellow-700 dark:text-yellow-300',
                'icon'   => 'heroicon-o-exclamation-triangle',
                'ic'     => 'text-yellow-500',
            ],
            'danger' => [
                'bg'     => 'bg-red-50 dark:bg-red-900/20',
                'border' => 'border-red-200 dark:border-red-800',
                'title'  => 'text-red-800 dark:text-red-200',
                'msg'    => 'text-red-700 dark:text-red-300',
                'icon'   => 'heroicon-o-x-circle',
                'ic'     => 'text-red-500',
            ],
        ];

        $cfg = $typeConfig[$type] ?? $typeConfig['info'];

        $iconHtml = '';
        if ($showIcon) {
            $iconHtml = '<div class="flex-shrink-0"><x-icon name="' . $cfg['icon'] . '" class="h-5 w-5 ' . $cfg['ic'] . '" /></div>';
        }

        return <<<HTML
<div class="fi-alert-card rounded-xl border {$cfg['border']} {$cfg['bg']} p-4 shadow-sm">
    <div class="flex gap-3">
        {$iconHtml}
        <div>
            <h3 class="text-sm font-semibold {$cfg['title']}">{$title}</h3>
            <p class="mt-1 text-sm {$cfg['msg']}">{$message}</p>
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
