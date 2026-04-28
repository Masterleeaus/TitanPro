<?php

declare(strict_types=1);

namespace App\Zeus\Widgets;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;
use LaraZeus\DynamicDashboard\Contracts\Widget;

/**
 * A simple HTML/text card that can be placed on any Dynamic Dashboard layout.
 *
 * Administrators create layouts via Filament Admin → Dynamic Dashboard →
 * Layouts. This widget provides a free-form text/HTML block so that at least
 * one block type is always available in the layout builder.
 */
class HtmlCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('html-card')
            ->label(__('HTML / Text Card'))
            ->icon('heroicon-o-document-text')
            ->schema([
                TextInput::make('title')
                    ->label(__('Title'))
                    ->placeholder(__('Card title (optional)')),

                Textarea::make('content')
                    ->label(__('Content'))
                    ->placeholder(__('HTML or plain text'))
                    ->rows(4)
                    ->required(),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title   = e($data['title'] ?? '');
        $content = $data['content'] ?? '';

        $html = '';

        if ($title !== '') {
            $html .= '<h3 class="text-lg font-semibold mb-2">' . $title . '</h3>';
        }

        $html .= '<div class="prose prose-sm max-w-none">' . $content . '</div>';

        return $html;
    }

    public function viewData(array $data): array
    {
        return $data;
    }
}
