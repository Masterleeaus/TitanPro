<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Budgeting\Filament\Resources\BudgetActualResource\Pages;
use Modules\Budgeting\Models\BudgetActual;

class BudgetActualResource extends Resource
{
    protected static ?string $model = BudgetActual::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string|\UnitEnum|null $navigationGroup = 'Budgeting';
    protected static ?string $navigationLabel = 'Budget Actuals';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('period_start')->date()->sortable(),
            TextColumn::make('period_end')->date()->sortable(),
            TextColumn::make('planned_amount')->money('AUD')->sortable(),
            TextColumn::make('actual_amount')->money('AUD')->sortable(),
            TextColumn::make('locked_at')->dateTime()->sortable()->toggleable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBudgetActuals::route('/'),
            'create' => Pages\CreateBudgetActual::route('/create'),
            'edit' => Pages\EditBudgetActual::route('/{record}/edit'),
        ];
    }
}
