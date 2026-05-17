<?php

namespace Modules\NexusGrowth\Filament\Pages;

use Filament\Pages\Page;

class PipelineFunnelPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-funnel';
    protected static ?string $navigationLabel = 'Pipeline Funnel';
    protected static ?string $navigationGroup = 'Intelligence';
    protected static ?int $navigationSort = 20;
    protected static string $view = 'nexusgrowth::pages.pipelinefunnelpage';

    public function getTitle(): string
    {
        return 'Pipeline Funnel';
    }
}
