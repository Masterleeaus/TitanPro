<?php

namespace Modules\BookingModule\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\BookingModule\Entities\Appointment;
use Modules\BookingModule\Filament\Resources\AppointmentResource\Pages;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Booking & Dispatch';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\Select::make('appointment_type')->options(['free'=>'Free','paid'=>'Paid']),
            Forms\Components\DatePicker::make('date'),
            Forms\Components\TimePicker::make('start_time'),
            Forms\Components\TimePicker::make('end_time'),
            Forms\Components\Toggle::make('is_enabled'),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('appointment_type'),
            Tables\Columns\IconColumn::make('is_enabled')->boolean(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }
    public static function getPages(): array
    {
        return ['index' => Pages\ListAppointments::route('/'), 'create' => Pages\CreateAppointment::route('/create'), 'edit' => Pages\EditAppointment::route('/{record}/edit')];
    }
}
