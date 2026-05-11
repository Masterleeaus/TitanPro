<?php

namespace App\Filament\TitanPro\Resources;

use App\Filament\TitanPro\Resources\ModuleResource\Pages;
use App\Models\Organization;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module as ModulesFacade;

class ModuleResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Modules';

    protected static ?string $modelLabel = 'Tenant Module Settings';

    protected static ?string $pluralModelLabel = 'Tenant Modules';

    public static function canViewAny(): bool
    {
        return static::isSuperAdmin();
    }

    public static function canCreate(): bool
    {
        return static::isSuperAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        return static::isSuperAdmin();
    }

    public static function canView(Model $record): bool
    {
        return static::isSuperAdmin();
    }

    public static function canDelete(Model $record): bool
    {
        return static::isSuperAdmin();
    }

    public static function canDeleteAny(): bool
    {
        return static::isSuperAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Tenant module enablement')
                ->schema([
                    Placeholder::make('organization')
                        ->label('Organization')
                        ->content(fn (Organization $record): string => $record->name),
                    CheckboxList::make('enabled_modules')
                        ->label('Enabled modules')
                        ->options(static::moduleOptions())
                        ->columns(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Organization')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('plan')
                    ->badge()
                    ->sortable(),
                TextColumn::make('enabled_modules')
                    ->label('Enabled Modules')
                    ->formatStateUsing(function (mixed $state): string {
                        if (! is_array($state) || count($state) === 0) {
                            return 'No tenant overrides';
                        }

                        return implode(', ', $state);
                    })
                    ->wrap(),
            ])
            ->recordActions([
                EditAction::make()->label('Manage Modules'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModules::route('/'),
            'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }

    private static function moduleOptions(): array
    {
        return collect(ModulesFacade::all())
            ->mapWithKeys(fn ($module): array => [$module->getName() => $module->getName()])
            ->sortKeys()
            ->all();
    }

    private static function isSuperAdmin(): bool
    {
        return (bool) auth()->user()?->hasRole('super_admin');
    }
}
