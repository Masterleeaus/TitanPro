<?php

namespace Modules\NexusGrowth\Filament\Pages;

use Filament\Pages\Page;

class PipelineFunnelPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-funnel';
    protected static ?string $navigationLabel = 'Pipeline Funnel';
    protected static string|\UnitEnum|null $navigationGroup = 'Intelligence';
    protected static ?int $navigationSort = 20;
    protected string $view = 'nexusgrowth::pages.pipelinefunnelpage';

    public function getTitle(): string
    {
        return 'Pipeline Funnel';
    }
}
