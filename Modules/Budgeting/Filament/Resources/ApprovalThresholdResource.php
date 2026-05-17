<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Budgeting\Filament\Resources\ApprovalThresholdResource\Pages;
use Modules\Budgeting\Models\ApprovalThreshold;

class ApprovalThresholdResource extends Resource
{
    protected static ?string $model = ApprovalThreshold::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';
    protected static string|\UnitEnum|null $navigationGroup = 'Budgeting';
    protected static ?string $navigationLabel = 'Approval Thresholds';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('min_amount')->money('AUD')->sortable(),
            TextColumn::make('max_amount')->money('AUD')->sortable()->placeholder('No limit'),
            TextColumn::make('approver_role')->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApprovalThresholds::route('/'),
            'create' => Pages\CreateApprovalThreshold::route('/create'),
            'edit' => Pages\EditApprovalThreshold::route('/{record}/edit'),
        ];
    }
}
