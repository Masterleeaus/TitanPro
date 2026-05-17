<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages;
use App\Models\TrainingContentModule;
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

class TrainingContentModuleResource extends Resource
{
    protected static ?string $model = TrainingContentModule::class;

    protected static ?string $slug = 'training-content';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Training Content';

    protected static ?string $modelLabel = 'Training Content Module';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Training Content Module')
                ->schema([
                    TextInput::make('title')->required()->maxLength(255),
                    TextInput::make('audience')->maxLength(255),
                    Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'archived' => 'Archived',
                        ])
                        ->required(),
                    TextInput::make('duration_minutes')->numeric()->minValue(1),
                    Textarea::make('description')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('audience')->searchable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('duration_minutes')->label('Duration (min)')->sortable(),
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
            'index' => Pages\ListTrainingContentModules::route('/'),
            'create' => Pages\CreateTrainingContentModule::route('/create'),
            'view' => Pages\ViewTrainingContentModule::route('/{record}'),
            'edit' => Pages\EditTrainingContentModule::route('/{record}/edit'),
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
