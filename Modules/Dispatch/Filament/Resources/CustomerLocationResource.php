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
use Modules\Dispatch\Filament\Resources\CustomerLocationResource\Pages;
use Modules\Dispatch\Models\CustomerLocation;

class CustomerLocationResource extends Resource
{
    protected static ?string $model = CustomerLocation::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Customer Locations';
    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Customer Locations')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->maxLength(255),
                    TextInput::make('address_line_1')->label('Address')->maxLength(255),
                    TextInput::make('suburb')->maxLength(100),
                    TextInput::make('state')->maxLength(100),
                    TextInput::make('postcode')->maxLength(20),
                    TextInput::make('country')->default('Australia')->maxLength(100),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('suburb')->searchable()->sortable(),
                TextColumn::make('state')->searchable()->sortable(),
                TextColumn::make('postcode')->searchable()->sortable(),
                TextColumn::make('serviceZone.name')->searchable()->sortable(),
                IconColumn::make('active')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerLocations::route('/'),
            'create' => Pages\CreateCustomerLocation::route('/create'),
            'edit' => Pages\EditCustomerLocation::route('/{record}/edit'),
        ];
    }
}
