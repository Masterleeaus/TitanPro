<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\NexusCallSessionResource\Pages;
use App\Models\TitanNexus\NexusCallSession;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NexusCallSessionResource extends Resource
{
    protected static ?string $model = NexusCallSession::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-phone';
    protected static string|\UnitEnum|null $navigationGroup = 'Voice Operations';
    protected static ?string $navigationLabel = 'Call Sessions';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                TextColumn::make('id')->searchable()->sortable()->toggleable(),TextColumn::make('provider')->badge()->searchable()->sortable(),TextColumn::make('direction')->badge()->searchable()->sortable(),TextColumn::make('from_number')->searchable()->sortable()->toggleable(),TextColumn::make('to_number')->searchable()->sortable()->toggleable(),TextColumn::make('status')->badge()->searchable()->sortable(),TextColumn::make('outcome')->searchable()->sortable()->toggleable(),TextColumn::make('duration_seconds')->searchable()->sortable()->toggleable(),TextColumn::make('created_at')->dateTime()->sortable()->toggleable()
            ])->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListNexusCallSessions::route('/')];
    }
}
