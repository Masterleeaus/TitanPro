<?php

namespace Modules\NexusGrowth\Filament\Pages;

use Filament\Pages\Page;

class ChurnPredictionPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-minus';
    protected static ?string $navigationLabel = 'Churn Prediction';
    protected static ?string $navigationGroup = 'Intelligence';
    protected static ?int $navigationSort = 30;
    protected static string $view = 'nexusgrowth::pages.churnpredictionpage';

    public function getTitle(): string
    {
        return 'Churn Prediction';
    }
}
