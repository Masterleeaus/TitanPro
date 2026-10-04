<?php

namespace Modules\CallingAgent\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\CallingAgent\Filament\Resources\CallingAgentCallerProfileResource\Pages;
use Modules\CallingAgent\Models\CallingAgentCallerProfile;

class CallingAgentCallerProfileResource extends Resource
{
    protected static ?string $model = CallingAgentCallerProfile::class;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Caller Profiles';
    protected static \UnitEnum|string|null $navigationGroup = 'Calling Agent';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('phone')->tel(),
            Forms\Components\TextInput::make('email')->email(),
            Forms\Components\TextInput::make('name'),
            Forms\Components\TextInput::make('company'),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('company')->searchable(),
                Tables\Columns\TextColumn::make('call_count')
                    ->label('Calls')
                    ->default(0),
                Tables\Columns\TextColumn::make('last_call_at')->dateTime()->sortable(),
            ])
            ->defaultSort('last_call_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCallingAgentCallerProfiles::route('/'),
            'edit'  => Pages\EditCallingAgentCallerProfile::route('/{record}/edit'),
        ];
    }
}
