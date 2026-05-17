<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Dispatch\Filament\Resources\DispatchRouteStopResource\Pages;
use Modules\Dispatch\Models\DispatchRouteStop;

class DispatchRouteStopResource extends Resource
{
    protected static ?string $model = DispatchRouteStop::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Route Stops';
    protected static ?int $navigationSort = 31;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Stop')
                ->columns(2)
                ->schema([
                    Select::make('dispatch_route_id')->relationship('route', 'name')->searchable()->preload()->required(),
                    TextInput::make('sequence')->numeric()->default(1)->required(),
                    Select::make('customer_location_id')->relationship('customerLocation', 'name')->searchable()->preload(),
                    Select::make('status')->options(['planned'=>'Planned','en_route'=>'En Route','on_site'=>'On Site','completed'=>'Completed','cancelled'=>'Cancelled'])->default('planned'),
                    DateTimePicker::make('planned_arrival_at'),
                    DateTimePicker::make('planned_departure_at'),
                    TextInput::make('travel_seconds_from_previous')->numeric()->label('Travel Seconds'),
                    TextInput::make('distance_meters_from_previous')->numeric()->label('Distance Meters'),
                    Textarea::make('notes')->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('route.name')->searchable()->sortable(),
                TextColumn::make('sequence')->sortable(),
                TextColumn::make('customerLocation.name')->label('Location')->searchable(),
                TextColumn::make('workOrder.id')->label('Job'),
                BadgeColumn::make('status'),
                TextColumn::make('planned_arrival_at')->dateTime()->sortable(),
                TextColumn::make('travel_seconds_from_previous')->label('Travel')->formatStateUsing(fn ($state) => $state ? round($state / 60).' min' : '—'),
            ])
            ->defaultSort('planned_arrival_at', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispatchRouteStops::route('/'),
            'create' => Pages\CreateDispatchRouteStop::route('/create'),
            'edit' => Pages\EditDispatchRouteStop::route('/{record}/edit'),
        ];
    }
}
