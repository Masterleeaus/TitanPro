<?php

namespace Modules\Accountings\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Accountings\Entities\Journal;
use Modules\Accountings\Filament\Resources\JournalResource\Pages;

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static string|\UnitEnum|null $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Journals';

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('no_journal')->label('Journal No')->searchable(),
            Tables\Columns\TextColumn::make('journal_date')->date(),
            Tables\Columns\TextColumn::make('reff_journal')->label('Reference')->toggleable(),
        ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournals::route('/'),
        ];
    }
}
