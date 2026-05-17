<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\ContactResource\Pages;
use App\Models\TitanNexus\NexusContact;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = NexusContact::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Acquisition';

    protected static ?string $navigationLabel = 'Contacts';

    protected static ?string $modelLabel = 'Contact';

    protected static ?string $pluralModelLabel = 'Contacts';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('name')->label('Name')->maxLength(255),
                TextInput::make('company')->label('Company')->maxLength(255),
                TextInput::make('email')->label('Email')->maxLength(255),
                TextInput::make('phone')->label('Phone')->maxLength(255),
                Select::make('status')->label('Status')->options(['draft'=>'Draft','new'=>'New','active'=>'Active','pending'=>'Pending','contacted'=>'Contacted','qualified'=>'Qualified','booked'=>'Booked','closed'=>'Closed','inactive'=>'Inactive'])->default('new'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id')->searchable()->sortable()->toggleable(),
                TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
                TextColumn::make('company')->label('Company')->searchable()->sortable()->toggleable(),
                TextColumn::make('email')->label('Email')->searchable()->sortable()->toggleable(),
                TextColumn::make('phone')->label('Phone')->searchable()->sortable()->toggleable(),
                TextColumn::make('status')->badge()->searchable()->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContact::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
