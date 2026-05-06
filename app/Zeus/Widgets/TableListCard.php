<?php

declare(strict_types=1);

namespace App\Zeus\Widgets;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;
use LaraZeus\DynamicDashboard\Contracts\Widget;

/**
 * A card that renders a simple table or list from JSON or line-separated data.
 *
 * Headers: comma-separated string — e.g. "Name,Status,Date"
 * Rows: JSON array of arrays — e.g. [["Job #1","Active","Today"],["Job #2","Done","Yesterday"]]
 */
class TableListCard implements Widget
{
    public function enabled(): bool
    {
        return true;
    }

    public function form(): Block
    {
        return Block::make('table-list-card')
            ->label(__('Table / List Card'))
            ->icon('heroicon-o-table-cells')
            ->schema([
                TextInput::make('title')
                    ->label(__('Table Title'))
                    ->placeholder(__('e.g. Recent Jobs'))
                    ->required(),

                TextInput::make('headers')
                    ->label(__('Column Headers (comma-separated)'))
                    ->placeholder(__('Name, Status, Date'))
                    ->helperText(__('Leave empty for a plain list.')),

                Textarea::make('rows')
                    ->label(__('Rows (JSON array of arrays)'))
                    ->placeholder(__('[["Job #1","Active","Today"],["Job #2","Done","Yesterday"]]'))
                    ->rows(5)
                    ->helperText(__('Each inner array is one row. Columns must match header count.')),

                TextInput::make('empty_message')
                    ->label(__('Empty State Message'))
                    ->default(__('No data available.')),
            ]);
    }

    public function renderWidget(array $data): string|View
    {
        $title        = e($data['title'] ?? '');
        $headersRaw   = $data['headers'] ?? '';
        $rowsRaw      = $data['rows'] ?? '[]';
        $emptyMessage = e($data['empty_message'] ?? 'No data available.');

        $headers = array_filter(array_map('trim', explode(',', $headersRaw)));

        $rows = [];
        if (is_string($rowsRaw) && $rowsRaw !== '') {
            $decoded = json_decode($rowsRaw, true);
            $rows    = is_array($decoded) ? $decoded : [];
        } elseif (is_array($rowsRaw)) {
            $rows = $rowsRaw;
        }

        if (empty($rows)) {
            return <<<HTML
<div class="fi-table-list-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">{$title}</h3>
    <p class="text-sm text-gray-400 dark:text-gray-500">{$emptyMessage}</p>
</div>
HTML;
        }

        $theadHtml = '';
        if (! empty($headers)) {
            $thCells = implode('', array_map(
                static fn ($h) => '<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">' . e($h) . '</th>',
                $headers
            ));
            $theadHtml = "<thead class=\"bg-gray-50 dark:bg-gray-700/50\"><tr>{$thCells}</tr></thead>";
        }

        $tbodyRows = '';
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $cells = implode('', array_map(
                static fn ($cell) => '<td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">' . e((string) $cell) . '</td>',
                $row
            ));
            $tbodyRows .= "<tr class=\"border-t border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors\">{$cells}</tr>";
        }

        return <<<HTML
<div class="fi-table-list-card rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{$title}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            {$theadHtml}
            <tbody>{$tbodyRows}</tbody>
        </table>
    </div>
</div>
HTML;
    }

    public function viewData(array $data): array
    {
        return $data;
    }
}
