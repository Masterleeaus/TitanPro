<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\NexusVoiceCallLogResource\Pages;
use App\Models\TitanNexus\NexusVoiceCallLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NexusVoiceCallLogResource extends Resource
{
    protected static ?string $model = NexusVoiceCallLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-phone';

    protected static string|\UnitEnum|null $navigationGroup = 'Outreach';

    protected static ?string $navigationLabel = 'Voice Call Logs';

    protected static ?string $modelLabel = 'Voice Call Log';

    protected static ?string $pluralModelLabel = 'Voice Call Logs';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('provider')->badge()->searchable()->sortable(),
                TextColumn::make('direction')->badge()->searchable()->sortable(),
                TextColumn::make('phone_number')->searchable()->sortable(),
                TextColumn::make('status')->badge()->searchable()->sortable(),
                TextColumn::make('duration_seconds')->label('Duration')->sortable(),
                TextColumn::make('summary')->limit(80)->toggleable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNexusVoiceCallLogs::route('/'),
        ];
    }
}
