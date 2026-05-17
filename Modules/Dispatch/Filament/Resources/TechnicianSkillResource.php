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
use Modules\Dispatch\Filament\Resources\TechnicianSkillResource\Pages;
use Modules\Dispatch\Models\TechnicianSkill;

class TechnicianSkillResource extends Resource
{
    protected static ?string $model = TechnicianSkill::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Technician Skills';
    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Technician Skills')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->maxLength(255),
                    Textarea::make('description')->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('technicians_count')->counts('technicians')->label('Technicians'),
                IconColumn::make('active')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTechnicianSkills::route('/'),
            'create' => Pages\CreateTechnicianSkill::route('/create'),
            'edit' => Pages\EditTechnicianSkill::route('/{record}/edit'),
        ];
    }
}
