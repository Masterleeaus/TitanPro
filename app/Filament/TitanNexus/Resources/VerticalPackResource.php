<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\VerticalPackResource\Pages;
use App\Models\VerticalPack;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VerticalPackResource extends Resource
{
    protected static ?string $model = VerticalPack::class;

    protected static ?string $slug = 'verticals';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Verticals';

    protected static ?string $modelLabel = 'Vertical Pack';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Vertical Pack')
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('slug')->required()->maxLength(255),
                    Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'active' => 'Active',
                            'archived' => 'Archived',
                        ])
                        ->required(),
                    Textarea::make('description')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->hasRole(['owner', 'admin']);
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canView($record);
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny()
            && $record->organization_id === auth()->user()?->organization_id;
    }

    public static function canDelete(Model $record): bool
    {
        return static::canView($record);
    }

    public static function canDeleteAny(): bool
    {
        return static::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVerticalPacks::route('/'),
            'create' => Pages\CreateVerticalPack::route('/create'),
            'view' => Pages\ViewVerticalPack::route('/{record}'),
            'edit' => Pages\EditVerticalPack::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()->where('organization_id', $organizationId);
    }
}
