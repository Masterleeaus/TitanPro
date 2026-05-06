<?php

namespace Modules\TitanZero\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

/**
 * TitanZeroEmbedWidget — collapsible AI panel for use in all node layouts.
 *
 * Auto-loads context from the current page (job ID, client ID, site ID).
 * Supports output modes: chat, generate document, generate report.
 *
 * Usage: add to any Filament resource page's getFooterWidgets() or getHeaderWidgets().
 */
class TitanZeroEmbedWidget extends Widget
{
    protected static ?string $heading = 'Ask Titan Zero';

    protected static ?int $sort = 99;

    protected static bool $isLazy = true;

    protected string $view = 'titanzero::filament.widgets.titan-zero-embed';

    /**
     * Context to pre-load into the panel. Populated by the hosting page.
     * e.g. ['job_id' => 12, 'client_id' => 5]
     */
    public array $aiContext = [];

    /**
     * Whether the panel starts expanded or collapsed.
     */
    public bool $expanded = false;

    /**
     * Output mode: chat | document | report
     */
    public string $outputMode = 'chat';

    protected function getViewData(): array
    {
        $user = Auth::user();

        return [
            'context'    => $this->aiContext,
            'expanded'   => $this->expanded,
            'outputMode' => $this->outputMode,
            'userName'   => $user?->name ?? 'User',
        ];
    }
}
