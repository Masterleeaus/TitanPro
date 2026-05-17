<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Dispatch\Filament\Resources\DispatchExceptionResource\Pages;
use Modules\Dispatch\Models\DispatchException;

class DispatchExceptionResource extends Resource
{
    protected static ?string $model = DispatchException::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Exceptions';
    protected static ?int $navigationSort = 85;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Exception')->columns(2)->schema([
                TextInput::make('work_order_id')->numeric(),
                TextInput::make('appointment_id')->numeric(),
                TextInput::make('technician_id')->numeric(),
                TextInput::make('type')->required()->maxLength(255),
                Select::make('severity')->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'])->default('medium'),
                Select::make('status')->options(['open' => 'Open', 'acknowledged' => 'Acknowledged', 'resolved' => 'Resolved'])->default('open'),
                Textarea::make('message')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('type')->searchable()->sortable(),
            TextColumn::make('severity')->badge()->sortable(),
            TextColumn::make('status')->badge()->sortable(),
            TextColumn::make('workOrder.reference')->label('Work Order')->searchable(),
            TextColumn::make('message')->limit(60),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispatchExceptions::route('/'),
            'create' => Pages\CreateDispatchException::route('/create'),
            'edit' => Pages\EditDispatchException::route('/{record}/edit'),
        ];
    }
}
