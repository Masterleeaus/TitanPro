<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\OutreachRunResource\Pages;
use App\Models\TitanNexus\NexusOutreachRun;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OutreachRunResource extends Resource
{
    protected static ?string $model = NexusOutreachRun::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|\UnitEnum|null $navigationGroup = 'Outreach';

    protected static ?string $navigationLabel = 'Outreach Runs';

    protected static ?string $modelLabel = 'Outreach Run';

    protected static ?string $pluralModelLabel = 'Outreach Runs';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('name')->label('Name')->maxLength(255),
                TextInput::make('title')->label('Offer Title')->maxLength(255),
                TextInput::make('channel')->label('Channel')->maxLength(255),
                TextInput::make('type')->label('Type')->maxLength(255),
                Select::make('status')->label('Status')->options(['draft'=>'Draft','new'=>'New','active'=>'Active','pending'=>'Pending','contacted'=>'Contacted','qualified'=>'Qualified','booked'=>'Booked','closed'=>'Closed','inactive'=>'Inactive'])->default('new'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id')->searchable()->sortable()->toggleable(),
                TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
                TextColumn::make('title')->label('Title')->searchable()->sortable()->toggleable(),
                TextColumn::make('channel')->label('Channel')->searchable()->sortable()->toggleable(),
                TextColumn::make('type')->label('Type')->searchable()->sortable()->toggleable(),
                TextColumn::make('status')->badge()->searchable()->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOutreachRun::route('/'),
            'create' => Pages\CreateOutreachRun::route('/create'),
            'edit' => Pages\EditOutreachRun::route('/{record}/edit'),
        ];
    }
}
