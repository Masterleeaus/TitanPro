<?php

namespace Modules\BookingModule\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\BookingModule\Models\CleaningBooking;
use Modules\BookingModule\Filament\Resources\BookingResource\Pages;

class BookingResource extends Resource
{
    protected static ?string $model = CleaningBooking::class;
    protected static ?string $navigationGroup = 'Booking & Dispatch';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('heading')->maxLength(255),
            Forms\Components\Select::make('booking_status')->options(['pending'=>'Pending','confirmed'=>'Confirmed','en_route'=>'En Route','in_progress'=>'In Progress','completed'=>'Completed','cancelled'=>'Cancelled','reclean'=>'Reclean']),
            Forms\Components\TextInput::make('service_type')->maxLength(80),
            Forms\Components\Textarea::make('service_address')->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('heading')->searchable(),
            Tables\Columns\TextColumn::make('booking_status')->badge(),
            Tables\Columns\TextColumn::make('service_type'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListBookings::route('/'), 'create' => Pages\CreateBooking::route('/create'), 'edit' => Pages\EditBooking::route('/{record}/edit')];
    }
}
