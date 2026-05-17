<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Budgeting\Filament\Resources\ForecastScenarioResource\Pages;
use Modules\Budgeting\Models\ForecastScenario;

class ForecastScenarioResource extends Resource
{
    protected static ?string $model = ForecastScenario::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static string|\UnitEnum|null $navigationGroup = 'Budgeting';
    protected static ?string $navigationLabel = 'Forecast Scenarios';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            BadgeColumn::make('status')
                ->colors([
                    'secondary' => 'draft',
                    'warning' => 'running',
                    'success' => 'ready',
                    'danger' => 'failed',
                ]),
            TextColumn::make('generated_at')->dateTime()->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForecastScenarios::route('/'),
            'create' => Pages\CreateForecastScenario::route('/create'),
            'edit' => Pages\EditForecastScenario::route('/{record}/edit'),
        ];
    }
}
