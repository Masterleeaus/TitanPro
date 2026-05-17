<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Dispatch\Filament\Resources\DispatchStatusLogResource\Pages;
use Modules\Dispatch\Models\DispatchStatusLog;

class DispatchStatusLogResource extends Resource
{
    protected static ?string $model = DispatchStatusLog::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Status Log';
    protected static ?int $navigationSort = 40;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('changed_at')->dateTime()->sortable(),
                TextColumn::make('work_order_id')->label('Job')->sortable(),
                BadgeColumn::make('from_status'),
                BadgeColumn::make('to_status'),
                TextColumn::make('changedBy.name')->label('Changed By'),
                TextColumn::make('notes')->limit(60),
            ])
            ->defaultSort('changed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispatchStatusLogs::route('/'),
        ];
    }
}
