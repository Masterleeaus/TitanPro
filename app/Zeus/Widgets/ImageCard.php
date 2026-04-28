<?php

declare(strict_types=1);

namespace App\Zeus\Widgets;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;
use LaraZeus\DynamicDashboard\Contracts\Widget;

/**
 * An image card with optional title and caption.
 */
class ImageCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('image-card')
            ->label(__('Image Card'))
            ->icon('heroicon-o-photo')
            ->schema([
                TextInput::make('title')
                    ->label(__('Title (optional)'))
                    ->placeholder(__('Card heading')),

                TextInput::make('image_url')
                    ->label(__('Image URL'))
                    ->placeholder(__('https://example.com/image.jpg or /storage/image.jpg'))
                    ->required(),

                TextInput::make('alt_text')
                    ->label(__('Alt Text'))
                    ->placeholder(__('Describe the image for accessibility'))
                    ->default(''),

                TextInput::make('caption')
                    ->label(__('Caption (optional)'))
                    ->placeholder(__('Image caption shown below')),

                Select::make('aspect_ratio')
                    ->label(__('Aspect Ratio'))
                    ->options([
                        'auto'   => 'Auto',
                        '16/9'   => '16:9 (Widescreen)',
                        '4/3'    => '4:3 (Standard)',
                        '1/1'    => '1:1 (Square)',
                        '3/1'    => '3:1 (Banner)',
                    ])
                    ->default('16/9'),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title       = e($data['title'] ?? '');
        $imageUrl    = e($data['image_url'] ?? '');
        $altText     = e($data['alt_text'] ?? '');
        $caption     = e($data['caption'] ?? '');
        $aspectRatio = $data['aspect_ratio'] ?? '16/9';

        if ($imageUrl === '') {
            return '<div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm text-center text-gray-400 dark:text-gray-500 text-sm">No image URL provided.</div>';
        }

        $ratioStyleMap = [
            'auto'   => '',
            '16/9'   => 'aspect-video',
            '4/3'    => 'aspect-4/3',
            '1/1'    => 'aspect-square',
            '3/1'    => 'aspect-[3/1]',
        ];

        $ratioClass = $ratioStyleMap[$aspectRatio] ?? 'aspect-video';

        $titleHtml = $title !== ''
            ? '<h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">' . $title . '</h3>'
            : '';

        $captionHtml = $caption !== ''
            ? '<p class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-center">' . $caption . '</p>'
            : '';

        return <<<HTML
<div class="fi-image-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm">
    {$titleHtml}
    <div class="overflow-hidden rounded-lg {$ratioClass} w-full bg-gray-100 dark:bg-gray-700">
        <img src="{$imageUrl}" alt="{$altText}" class="h-full w-full object-cover" loading="lazy" />
    </div>
    {$captionHtml}
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }
}
