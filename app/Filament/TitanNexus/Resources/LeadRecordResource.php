<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\LeadRecordResource\Pages;
use App\Models\TitanNexus\TitanNexusLead;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeadRecordResource extends Resource
{
    protected static ?string $model = TitanNexusLead::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-funnel';

    protected static string|\UnitEnum|null $navigationGroup = 'Acquisition';

    protected static ?string $navigationLabel = 'Lead Records';

    protected static ?string $modelLabel = 'Lead Record';

    protected static ?string $pluralModelLabel = 'Lead Records';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('name')->label('Name')->maxLength(255),
                TextInput::make('company')->label('Company')->maxLength(255),
                TextInput::make('email')->label('Email')->maxLength(255),
                TextInput::make('phone')->label('Phone')->maxLength(255),
                TextInput::make('score')->label('Score')->numeric(),
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
                TextColumn::make('score')->label('Score')->searchable()->sortable()->toggleable(),
                TextColumn::make('status')->badge()->searchable()->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeadRecord::route('/'),
            'create' => Pages\CreateLeadRecord::route('/create'),
            'edit' => Pages\EditLeadRecord::route('/{record}/edit'),
        ];
    }
}
