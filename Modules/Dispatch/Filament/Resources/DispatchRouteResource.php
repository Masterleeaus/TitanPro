<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Modules\Dispatch\Filament\Resources\DispatchRouteResource\Pages;
use Modules\Dispatch\Models\DispatchRoute;

class DispatchRouteResource extends Resource
{
    protected static ?string $model = DispatchRoute::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Routes';
    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Routes')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->maxLength(255),
                    Select::make('technician_id')->label('Technician')->relationship('technician', 'name')->searchable()->preload(),
                    DatePicker::make('route_date')->required(),
                    Select::make('status')->options(['draft'=>'Draft','planned'=>'Planned','active'=>'Active','completed'=>'Completed','cancelled'=>'Cancelled'])->default('draft'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('route_date')->date()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('technician.name')->searchable()->sortable(),
                BadgeColumn::make('status'),
                TextColumn::make('stops_count')->counts('stops')->label('Stops'),
                TextColumn::make('total_duration_seconds')->label('Drive Time')->formatStateUsing(fn ($state) => $state ? round($state / 60).' min' : '—'),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispatchRoutes::route('/'),
            'create' => Pages\CreateDispatchRoute::route('/create'),
            'edit' => Pages\EditDispatchRoute::route('/{record}/edit'),
        ];
    }
}
