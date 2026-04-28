<?php

namespace Modules\CRMCore\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages;
use Modules\CRMCore\Models\CRMCoreActivityLog;

class CRMCoreActivityLogResource extends Resource
{
    protected static ?string $model = CRMCoreActivityLog::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static string|\UnitEnum|null $navigationGroup = 'CRM Automation';
    protected static ?string $navigationLabel = 'Activity Log';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Activity')->schema([]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('event')->badge()->searchable()->sortable(),
            TextColumn::make('subject_type')->searchable()->toggleable(),
            TextColumn::make('subject_id')->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCRMCoreActivityLogs::route('/'),
        ];
    }
}
