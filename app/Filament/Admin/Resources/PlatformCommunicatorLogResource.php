<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PlatformCommunicatorLogResource\Pages;
use App\Models\PlatformCommunicatorLog;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlatformCommunicatorLogResource extends Resource
{
    protected static ?string $model = PlatformCommunicatorLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform Commerce';

    protected static ?string $navigationLabel = 'Communications';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Message')
                ->columns(['sm' => 1, 'lg' => 2])
                ->schema([
                    Select::make('channel')->options(['email' => 'Email', 'sms' => 'SMS', 'in_app' => 'In-app'])->default('email')->required(),
                    Select::make('status')->options(['draft' => 'Draft', 'queued' => 'Queued', 'sent' => 'Sent', 'failed' => 'Failed'])->default('draft'),
                    TextInput::make('audience')->helperText('Example: all owners, trial users, expired plans'),
                    TextInput::make('recipient_count')->numeric()->default(0),
                    TextInput::make('subject')->columnSpanFull(),
                    RichEditor::make('message')->columnSpanFull(),
                    KeyValue::make('metadata')->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')->searchable()->sortable(),
                TextColumn::make('channel')->badge(),
                TextColumn::make('audience')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('recipient_count')->sortable(),
                TextColumn::make('sent_at')->dateTime()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlatformCommunications::route('/'),
            'create' => Pages\CreatePlatformCommunication::route('/create'),
            'edit' => Pages\EditPlatformCommunication::route('/{record}/edit'),
        ];
    }
}
