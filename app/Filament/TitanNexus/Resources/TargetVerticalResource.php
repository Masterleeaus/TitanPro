<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\TargetVerticalResource\Pages;
use App\Models\TitanNexus\TitanNexusCampaign;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TargetVerticalResource extends Resource
{
    protected static ?string $model = TitanNexusCampaign::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanNexus';

    protected static ?string $navigationLabel = 'Target Verticals';

    protected static ?string $modelLabel = 'Target Vertical';

    protected static ?string $pluralModelLabel = 'Target Verticals';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('name')->label('Name')->maxLength(255),
                TextInput::make('vertical')->label('Service Vertical')->maxLength(255),
                Select::make('status')->label('Status')->options(['draft'=>'Draft','new'=>'New','active'=>'Active','pending'=>'Pending','contacted'=>'Contacted','qualified'=>'Qualified','booked'=>'Booked','closed'=>'Closed','inactive'=>'Inactive'])->default('new'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id')->searchable()->sortable()->toggleable(),
                TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
                TextColumn::make('vertical')->label('Vertical')->searchable()->sortable()->toggleable(),
                TextColumn::make('status')->badge()->searchable()->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTargetVertical::route('/'),
            'create' => Pages\CreateTargetVertical::route('/create'),
            'edit' => Pages\EditTargetVertical::route('/{record}/edit'),
        ];
    }
}
