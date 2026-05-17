<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\NexusCallbackRequestResource\Pages;
use App\Models\TitanNexus\NexusCallbackRequest;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NexusCallbackRequestResource extends Resource
{
    protected static ?string $model = NexusCallbackRequest::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static string|\UnitEnum|null $navigationGroup = 'Voice Operations';
    protected static ?string $navigationLabel = 'Callback Requests';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                TextColumn::make('id')->searchable()->sortable()->toggleable(),TextColumn::make('name')->searchable()->sortable()->toggleable(),TextColumn::make('phone')->searchable()->sortable()->toggleable(),TextColumn::make('status')->badge()->searchable()->sortable(),TextColumn::make('priority')->badge()->searchable()->sortable(),TextColumn::make('due_at')->dateTime()->sortable()->toggleable(),TextColumn::make('created_at')->dateTime()->sortable()->toggleable()
            ])->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListNexusCallbackRequests::route('/')];
    }
}
