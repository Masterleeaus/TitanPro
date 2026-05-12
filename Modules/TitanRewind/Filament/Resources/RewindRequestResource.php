<?php

namespace Modules\TitanRewind\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\TitanRewind\Filament\Resources\Concerns\OwnerOnlyRewindAccess;
use Modules\TitanRewind\Filament\Resources\RewindRequestResource\Pages;
use Modules\TitanRewind\Models\RewindFix;

class RewindRequestResource extends Resource
{
    use OwnerOnlyRewindAccess;

    protected static ?string $model = RewindFix::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Rewind';

    protected static ?string $navigationLabel = 'Rewind Requests';

    protected static ?int $navigationSort = 15;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Rewind Request')->schema([]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('case_id')->sortable(),
            TextColumn::make('fix_type')->badge(),
            TextColumn::make('status')->badge(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRewindRequests::route('/'),
        ];
    }
}
