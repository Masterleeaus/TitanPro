<?php

namespace Modules\Biometric\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Biometric\Entities\BiometricDevice;
use Modules\Biometric\Filament\Resources\BiometricDeviceResource\Pages\ListBiometricDevices;

class BiometricDeviceResource extends Resource
{
    protected static ?string $model = BiometricDevice::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';
    protected static string|\UnitEnum|null $navigationGroup = 'Biometric';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Devices';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('serial_number')->searchable(),
                Tables\Columns\TextColumn::make('device_ip')->label('IP')->toggleable(),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'success' => 'online',
                    'danger' => 'offline',
                    'warning' => 'pending',
                ]),
                Tables\Columns\TextColumn::make('last_online')->dateTime()->sortable(),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBiometricDevices::route('/'),
        ];
    }
}

