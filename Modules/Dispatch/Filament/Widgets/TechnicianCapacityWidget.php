<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Dispatch\Models\TechnicianProfile;

class TechnicianCapacityWidget extends TableWidget
{
    protected static ?string $heading = 'Technician capacity';

    public function table(Table $table): Table
    {
        return $table
            ->query(TechnicianProfile::query()->where('active', true))
            ->columns([
                Tables\Columns\TextColumn::make('display_name')->label('Technician')->searchable(),
                Tables\Columns\TextColumn::make('capacity_minutes_per_day')->label('Daily minutes'),
                Tables\Columns\IconColumn::make('active')->boolean(),
            ]);
    }
}
