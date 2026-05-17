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
use Modules\Dispatch\Filament\Resources\ServiceZoneResource\Pages;
use Modules\Dispatch\Models\ServiceZone;

class ServiceZoneResource extends Resource
{
    protected static ?string $model = ServiceZone::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Service Zones';
    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Service Zones')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->maxLength(255),
                    TextInput::make('code')->maxLength(50),
                    Textarea::make('description')->columnSpanFull(),
                    TextInput::make('center_latitude')->numeric(),
                    TextInput::make('center_longitude')->numeric(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('customer_locations_count')->counts('customerLocations')->label('Customer Locations'),
                IconColumn::make('active')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceZones::route('/'),
            'create' => Pages\CreateServiceZone::route('/create'),
            'edit' => Pages\EditServiceZone::route('/{record}/edit'),
        ];
    }
}
