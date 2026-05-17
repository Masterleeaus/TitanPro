<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Modules\Budgeting\Filament\Resources\VarianceResource\Pages;
use Modules\Budgeting\Models\BudgetVariance;

class VarianceResource extends Resource
{
    protected static ?string $model = BudgetVariance::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static string|\UnitEnum|null $navigationGroup = 'Budgeting';
    protected static ?string $navigationLabel = 'Variances';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('variance_amount')->money('AUD')->sortable(),
            TextColumn::make('variance_pct')->suffix('%')->sortable(),
            BadgeColumn::make('flag')
                ->colors([
                    'success' => 'normal',
                    'warning' => 'warning',
                    'danger' => 'critical',
                ]),
            IconColumn::make('anomaly_flagged')->boolean(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVariances::route('/'),
            'create' => Pages\CreateVariance::route('/create'),
            'edit' => Pages\EditVariance::route('/{record}/edit'),
        ];
    }
}
