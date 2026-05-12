<?php

namespace Modules\TitanRewind\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\TitanRewind\Filament\Resources\Concerns\OwnerOnlyRewindAccess;
use Modules\TitanRewind\Filament\Resources\RewindEventResource\Pages;
use Modules\TitanRewind\Models\RewindEvent;

class RewindEventResource extends Resource
{
    use OwnerOnlyRewindAccess;

    protected static ?string $model = RewindEvent::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Rewind';

    protected static ?string $navigationLabel = 'Event Timeline';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Event Timeline')->schema([]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('case_id')->sortable(),
            TextColumn::make('event_type')->badge(),
            TextColumn::make('entity_type')->toggleable(),
            TextColumn::make('entity_id')->toggleable(),
            TextColumn::make('actor_type')->badge(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRewindEvents::route('/'),
        ];
    }
}
