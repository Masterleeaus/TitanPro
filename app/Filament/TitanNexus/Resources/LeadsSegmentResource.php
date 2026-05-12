<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\LeadsSegmentResource\Pages;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\TitanLeads\Models\Whatsapp\Segment;

class LeadsSegmentResource extends Resource
{
    protected static ?string $model = Segment::class;

    protected static ?string $slug = 'leads-segments';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Segments';

    protected static ?string $navigationGroup = 'Titan Leads';

    protected static ?int $navigationSort = 52;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Segment')
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    Toggle::make('status')->label('Active')->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                IconColumn::make('status')->boolean()->label('Active'),
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
        return static::canViewAny();
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDeleteAny(): bool
    {
        return static::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLeadsSegments::route('/'),
            'create' => Pages\CreateLeadsSegment::route('/create'),
            'view'   => Pages\ViewLeadsSegment::route('/{record}'),
            'edit'   => Pages\EditLeadsSegment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->id();

        if ($userId === null) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()->where('user_id', $userId);
    }
}
