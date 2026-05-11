<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages;
use App\Models\LeadPipelineEntry;
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

class LeadPipelineEntryResource extends Resource
{
    protected static ?string $model = LeadPipelineEntry::class;

    protected static ?string $slug = 'lead-pipeline';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationLabel = 'Lead Pipeline';

    protected static ?string $modelLabel = 'Lead Pipeline Entry';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Lead Pipeline Entry')
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('email')->email()->maxLength(255),
                    TextInput::make('source')->maxLength(255),
                    Select::make('stage')
                        ->options([
                            'new' => 'New',
                            'qualified' => 'Qualified',
                            'proposal' => 'Proposal',
                            'won' => 'Won',
                            'lost' => 'Lost',
                        ])
                        ->required(),
                    Textarea::make('notes')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('source')->searchable(),
                TextColumn::make('stage')->badge()->sortable(),
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
            'index' => Pages\ListLeadPipelineEntries::route('/'),
            'create' => Pages\CreateLeadPipelineEntry::route('/create'),
            'view' => Pages\ViewLeadPipelineEntry::route('/{record}'),
            'edit' => Pages\EditLeadPipelineEntry::route('/{record}/edit'),
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
