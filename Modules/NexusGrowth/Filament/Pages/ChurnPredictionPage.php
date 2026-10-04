<?php

namespace Modules\NexusGrowth\Filament\Pages;

use Filament\Pages\Page;

class ChurnPredictionPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-minus';
    protected static ?string $navigationLabel = 'Churn Prediction';
    protected static string|\UnitEnum|null $navigationGroup = 'Intelligence';
    protected static ?int $navigationSort = 30;
    protected string $view = 'nexusgrowth::pages.churnpredictionpage';

    public function getTitle(): string
    {
        return 'Churn Prediction';
    }
}
