<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Dispatch\Filament\Resources\TechnicianProfileResource\Pages;
use Modules\Dispatch\Models\TechnicianProfile;

class TechnicianProfileResource extends Resource
{
    protected static ?string $model = TechnicianProfile::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Technicians';
    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Technicians')
                ->columns(2)
                ->schema([
                    Select::make('user_id')->label('User')->relationship('user', 'name')->searchable()->preload(),
                    TextInput::make('display_name')->maxLength(255),
                    TextInput::make('phone')->tel()->maxLength(50),
                    TextInput::make('capacity_minutes_per_day')->numeric()->default(480),
                    Select::make('default_zone_id')->label('Default Zone')->relationship('defaultZone', 'name')->searchable()->preload(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name')->searchable()->sortable(),
                TextColumn::make('user.name')->searchable()->sortable(),
                TextColumn::make('phone')->searchable()->sortable(),
                TextColumn::make('defaultZone.name')->searchable()->sortable(),
                TextColumn::make('capacity_minutes_per_day')->searchable()->sortable(),
                IconColumn::make('active')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTechnicianProfiles::route('/'),
            'create' => Pages\CreateTechnicianProfile::route('/create'),
            'edit' => Pages\EditTechnicianProfile::route('/{record}/edit'),
        ];
    }
}
