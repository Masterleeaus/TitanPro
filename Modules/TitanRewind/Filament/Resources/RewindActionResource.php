<?php

namespace Modules\TitanRewind\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\TitanRewind\Filament\Resources\Concerns\OwnerOnlyRewindAccess;
use Modules\TitanRewind\Filament\Resources\RewindActionResource\Pages;
use Modules\TitanRewind\Models\RewindAction;

class RewindActionResource extends Resource
{
    use OwnerOnlyRewindAccess;

    protected static ?string $model = RewindAction::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Rewind';

    protected static ?string $navigationLabel = 'Action Log';

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Action Log')->schema([]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('case_id')->sortable(),
            TextColumn::make('action_type')->badge(),
            TextColumn::make('target_type')->toggleable(),
            TextColumn::make('target_id')->toggleable(),
            IconColumn::make('success')->boolean(),
            TextColumn::make('executed_by_type')->badge(),
            TextColumn::make('executed_at')->dateTime()->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRewindActions::route('/'),
        ];
    }
}
