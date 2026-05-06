<?php

namespace Modules\TitanNexus\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\TitanNexus\Filament\Resources\LeadRecordResource\Pages;
use Modules\TitanNexus\Models\LeadRecord;

class LeadRecordResource extends Resource
{
    protected static ?string $model = LeadRecord::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static \UnitEnum|string|null $navigationGroup = 'Titan Nexus';

    protected static ?string $navigationLabel = 'Lead Records';

    protected static ?string $modelLabel = 'Lead Record';

    protected static ?string $pluralModelLabel = 'Lead Records';

    protected static ?int $navigationSort = 10;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('titan_nexus.view') ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company_id')
                    ->label('Company')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('user_id')
                    ->label('User')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeadRecords::route('/'),
        ];
    }
}
