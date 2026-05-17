<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources;

use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Dispatch\Filament\Resources\ShiftResource\Pages;
use Modules\Dispatch\Models\Shift;

class ShiftResource extends Resource
{
    protected static ?string $model = Shift::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Shift Templates';
    protected static ?string $modelLabel = 'Shift Template';
    protected static ?string $pluralModelLabel = 'Shift Templates';
    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Shift Window')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    Select::make('type')
                        ->options([1 => 'Recurring', 2 => 'Cyclic', 3 => 'Free'])
                        ->default(1)
                        ->required(),
                    TextInput::make('start_time')->label('Start')->placeholder('08:00')->maxLength(20),
                    TextInput::make('finish_time')->label('Finish')->placeholder('17:00')->maxLength(20),
                    TextInput::make('break_time')->numeric()->suffix('minutes'),
                    TextInput::make('tag')->maxLength(255),
                    Select::make('publish')->options([1 => 'Published', 0 => 'Draft'])->default(1),
                    Select::make('range')->options([0 => 'No', 1 => 'Yes'])->default(0),
                    Textarea::make('note')->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                BadgeColumn::make('type')
                    ->formatStateUsing(fn ($state): string => [1 => 'Recurring', 2 => 'Cyclic', 3 => 'Free'][(int) $state] ?? 'Shift'),
                TextColumn::make('start_time')->label('Start'),
                TextColumn::make('finish_time')->label('Finish'),
                TextColumn::make('assignments_count')->counts('assignments')->label('Assignments'),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShifts::route('/'),
            'create' => Pages\CreateShift::route('/create'),
            'edit' => Pages\EditShift::route('/{record}/edit'),
        ];
    }
}
