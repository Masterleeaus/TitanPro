<?php

namespace Modules\TitanRewind\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\TitanRewind\Filament\Resources\Concerns\OwnerOnlyRewindAccess;
use Modules\TitanRewind\Filament\Resources\RewindCaseResource\Pages;
use Modules\TitanRewind\Models\RewindCase;

class RewindCaseResource extends Resource
{
    use OwnerOnlyRewindAccess;

    protected static ?string $model = RewindCase::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Rewind';

    protected static ?string $navigationLabel = 'Rewind Cases';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Rewind Case')->schema([]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->searchable()->limit(40),
            TextColumn::make('status')->badge(),
            TextColumn::make('severity')->badge(),
            TextColumn::make('entity_type')->toggleable(),
            TextColumn::make('entity_id')->toggleable(),
            TextColumn::make('detected_at')->dateTime()->sortable(),
            TextColumn::make('resolved_at')->dateTime()->toggleable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRewindCases::route('/'),
        ];
    }
}
