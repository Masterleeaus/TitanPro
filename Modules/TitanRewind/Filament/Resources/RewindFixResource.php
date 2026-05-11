<?php

namespace Modules\TitanRewind\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\TitanRewind\Filament\Resources\Concerns\OwnerOnlyRewindAccess;
use Modules\TitanRewind\Filament\Resources\RewindFixResource\Pages;
use Modules\TitanRewind\Models\RewindFix;

class RewindFixResource extends Resource
{
    use OwnerOnlyRewindAccess;

    protected static ?string $model = RewindFix::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-light-bulb';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Rewind';

    protected static ?string $navigationLabel = 'Fix Proposals';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Fix Proposal')->schema([]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('case_id')->sortable(),
            TextColumn::make('fix_type')->badge(),
            TextColumn::make('proposed_by_type')->badge(),
            TextColumn::make('status')->badge(),
            TextColumn::make('created_at')->dateTime()->sortable(),
            TextColumn::make('applied_at')->dateTime()->toggleable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRewindFixes::route('/'),
        ];
    }
}
