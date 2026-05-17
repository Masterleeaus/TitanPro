<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\NexusCallRecordingResource\Pages;
use App\Models\TitanNexus\NexusCallRecording;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NexusCallRecordingResource extends Resource
{
    protected static ?string $model = NexusCallRecording::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-microphone';
    protected static string|\UnitEnum|null $navigationGroup = 'Voice Operations';
    protected static ?string $navigationLabel = 'Call Recordings';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                TextColumn::make('id')->searchable()->sortable()->toggleable(),TextColumn::make('provider')->badge()->searchable()->sortable(),TextColumn::make('recording_url')->searchable()->sortable()->toggleable(),TextColumn::make('duration_seconds')->searchable()->sortable()->toggleable(),TextColumn::make('status')->badge()->searchable()->sortable(),TextColumn::make('expires_at')->dateTime()->sortable()->toggleable(),TextColumn::make('created_at')->dateTime()->sortable()->toggleable()
            ])->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListNexusCallRecordings::route('/')];
    }
}
