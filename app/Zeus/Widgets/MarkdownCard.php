<?php

declare(strict_types=1);

namespace App\Zeus\Widgets;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Contracts\View\View;
use LaraZeus\DynamicDashboard\Contracts\Widget;

/**
 * A Markdown text card for Dynamic Dashboard layouts.
 *
 * Renders safe Markdown using PHP's built-in string processing.
 * HTML tags in the source are escaped unless "allow_html" is enabled
 * by an administrator (disabled by default for safety).
 */
class MarkdownCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('markdown-card')
            ->label(__('Markdown Card'))
            ->icon('heroicon-o-pencil-square')
            ->schema([
                TextInput::make('title')
                    ->label(__('Title (optional)'))
                    ->placeholder(__('Card heading')),

                Textarea::make('content')
                    ->label(__('Markdown Content'))
                    ->placeholder(__("# Heading\n\nSome **bold** text and a [link](https://example.com)."))
                    ->rows(6)
                    ->required(),

                Toggle::make('allow_html')
                    ->label(__('Allow raw HTML in Markdown (advanced)'))
                    ->helperText(__('Leave off unless you trust all editors. Raw HTML will be escaped when off.'))
                    ->default(false),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title     = e($data['title'] ?? '');
        $content   = $data['content'] ?? '';
        $allowHtml = (bool) ($data['allow_html'] ?? false);

        if (! $allowHtml) {
            // Escape HTML entities before Markdown parsing so raw HTML cannot be injected
            $content = htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $rendered = $this->renderMarkdown($content);

        $titleHtml = $title !== ''
            ? '<h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">' . e($title) . '</h3>'
            : '';

        return <<<HTML
<div class="fi-markdown-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
    {$titleHtml}
    <div class="prose prose-sm dark:prose-invert max-w-none">
        {$rendered}
    </div>
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }

    /**
     * Convert a subset of Markdown to HTML.
     *
     * Supports: headings, bold, italic, inline code, code blocks,
     * unordered lists, ordered lists, horizontal rules, paragraphs,
     * and links.
     */
    private function renderMarkdown(string $text): string
    {
        if (class_exists(\League\CommonMark\CommonMarkConverter::class)) {
            $converter = new \League\CommonMark\CommonMarkConverter([
                'html_input' => 'escape',
                'allow_unsafe_links' => false,
            ]);

            return $converter->convert($text)->getContent();
        }

        // Minimal built-in Markdown-like parser (no external dependency required).
        $lines  = explode("\n", $text);
        $output = '';
        $inList = false;
        $listTag = '';
        $inCode = false;
        $codeBuffer = '';

        foreach ($lines as $line) {
            // Fenced code blocks
            if (str_starts_with($line, '```')) {
                if ($inCode) {
                    $inCode = false;
                    $output .= '<pre class="rounded bg-gray-100 dark:bg-gray-700 p-3 text-sm overflow-x-auto"><code>' . htmlspecialchars($codeBuffer, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</code></pre>';
                    $codeBuffer = '';
                } else {
                    $inCode = true;
                    if ($inList) {
                        $output .= "</{$listTag}>";
                        $inList = false;
                        $listTag = '';
                    }
                }
                continue;
            }

            if ($inCode) {
                $codeBuffer .= $line . "\n";
                continue;
            }

            // Close open list if line is not a list item
            if ($inList && ! preg_match('/^(\s*[-*+]|\s*\d+\.) /', $line)) {
                if (trim($line) === '') {
                    $output .= "</{$listTag}>";
                    $inList = false;
                    $listTag = '';
                    continue;
                }
            }

            // Headings
            if (preg_match('/^(#{1,6})\s+(.+)$/', $line, $m)) {
                if ($inList) {
                    $output .= "</{$listTag}>";
                    $inList = false;
                    $listTag = '';
                }
                $level   = strlen($m[1]);
                $heading = $this->parseInline($m[2]);
                $sizes   = ['text-2xl', 'text-xl', 'text-lg', 'text-base', 'text-sm', 'text-xs'];
                $size    = $sizes[$level - 1] ?? 'text-base';
                $output .= "<h{$level} class=\"font-bold {$size} mt-3 mb-1\">{$heading}</h{$level}>\n";
                continue;
            }

            // Horizontal rules
            if (preg_match('/^(-{3,}|\*{3,}|_{3,})$/', trim($line))) {
                $output .= '<hr class="my-3 border-gray-200 dark:border-gray-600" />' . "\n";
                continue;
            }

            // Unordered list
            if (preg_match('/^\s*[-*+] (.+)$/', $line, $m)) {
                if (! $inList || $listTag !== 'ul') {
                    if ($inList) {
                        $output .= "</{$listTag}>";
                    }
                    $output .= '<ul class="list-disc list-inside space-y-0.5">';
                    $inList  = true;
                    $listTag = 'ul';
                }
                $output .= '<li class="text-sm">' . $this->parseInline($m[1]) . '</li>';
                continue;
            }

            // Ordered list
            if (preg_match('/^\s*\d+\. (.+)$/', $line, $m)) {
                if (! $inList || $listTag !== 'ol') {
                    if ($inList) {
                        $output .= "</{$listTag}>";
                    }
                    $output .= '<ol class="list-decimal list-inside space-y-0.5">';
                    $inList  = true;
                    $listTag = 'ol';
                }
                $output .= '<li class="text-sm">' . $this->parseInline($m[1]) . '</li>';
                continue;
            }

            // Blank line
            if (trim($line) === '') {
                if ($inList) {
                    $output .= "</{$listTag}>";
                    $inList = false;
                    $listTag = '';
                }
                continue;
            }

            // Paragraph
            if ($inList) {
                $output .= "</{$listTag}>";
                $inList = false;
                $listTag = '';
            }
            $output .= '<p class="text-sm mb-2">' . $this->parseInline($line) . '</p>' . "\n";
        }

        // Close any open list
        if ($inList) {
            $output .= "</{$listTag}>";
        }

        // Close open code block
        if ($inCode && $codeBuffer !== '') {
            $output .= '<pre class="rounded bg-gray-100 dark:bg-gray-700 p-3 text-sm overflow-x-auto"><code>' . htmlspecialchars($codeBuffer, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</code></pre>';
        }

        return $output;
    }

    private function parseInline(string $text): string
    {
        // Inline code
        $text = preg_replace('/`([^`]+)`/', '<code class="rounded bg-gray-100 dark:bg-gray-700 px-1 text-xs font-mono">$1</code>', $text) ?? $text;
        // Bold
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text) ?? $text;
        // Italic
        $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text) ?? $text;
        // Links — callback form so the URL is HTML-escaped before insertion
        $text = preg_replace_callback(
            '/\[(.+?)\]\((https?:\/\/[^\)]+)\)/',
            static function (array $m): string {
                $label = $m[1];
                $url   = htmlspecialchars($m[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');

                return '<a href="' . $url . '" class="underline text-primary-600 dark:text-primary-400" rel="noopener noreferrer" target="_blank">' . $label . '</a>';
            },
            $text
        ) ?? $text;

        return $text;
    }
}
