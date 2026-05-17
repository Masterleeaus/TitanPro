<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\BookingHandoffResource\Pages;
use App\Models\TitanNexus\NexusBookingHandoff;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingHandoffResource extends Resource
{
    protected static ?string $model = NexusBookingHandoff::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'Conversion';

    protected static ?string $navigationLabel = 'Booking Handoffs';

    protected static ?string $modelLabel = 'Booking Handoff';

    protected static ?string $pluralModelLabel = 'Booking Handoffs';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('lead_id')->label('Lead ID')->numeric(),
                Select::make('status')->label('Status')->options(['draft'=>'Draft','new'=>'New','active'=>'Active','pending'=>'Pending','contacted'=>'Contacted','qualified'=>'Qualified','booked'=>'Booked','closed'=>'Closed','inactive'=>'Inactive'])->default('new'),
                DateTimePicker::make('scheduled_at')->label('Scheduled At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id')->searchable()->sortable()->toggleable(),
                TextColumn::make('lead_id')->label('Lead Id')->searchable()->sortable()->toggleable(),
                TextColumn::make('status')->badge()->searchable()->sortable(),
                TextColumn::make('scheduled_at')->label('Scheduled At')->dateTime()->sortable()->toggleable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingHandoff::route('/'),
            'create' => Pages\CreateBookingHandoff::route('/create'),
            'edit' => Pages\EditBookingHandoff::route('/{record}/edit'),
        ];
    }
}
