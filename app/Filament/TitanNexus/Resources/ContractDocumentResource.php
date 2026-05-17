<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\ContractDocumentResource\Pages;
use App\Models\TitanNexus\NexusContractDocument;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContractDocumentResource extends Resource
{
    protected static ?string $model = NexusContractDocument::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Delivery Readiness';

    protected static ?string $navigationLabel = 'Contracts & Paperwork';

    protected static ?string $modelLabel = 'Contract Document';

    protected static ?string $pluralModelLabel = 'Contracts & Paperwork';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('title')->label('Title')->maxLength(255),
                TextInput::make('vertical')->label('Service Vertical')->maxLength(255),
                TextInput::make('document_type')->label('Document Type')->maxLength(255),
                Select::make('status')->label('Status')->options(['draft'=>'Draft','new'=>'New','active'=>'Active','pending'=>'Pending','contacted'=>'Contacted','qualified'=>'Qualified','booked'=>'Booked','closed'=>'Closed','inactive'=>'Inactive'])->default('new'),
                Textarea::make('content')->label('Instructions / Details')->rows(8)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id')->searchable()->sortable()->toggleable(),
                TextColumn::make('title')->label('Title')->searchable()->sortable()->toggleable(),
                TextColumn::make('vertical')->label('Vertical')->searchable()->sortable()->toggleable(),
                TextColumn::make('document_type')->label('Document Type')->searchable()->sortable()->toggleable(),
                TextColumn::make('status')->badge()->searchable()->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContractDocument::route('/'),
            'create' => Pages\CreateContractDocument::route('/create'),
            'edit' => Pages\EditContractDocument::route('/{record}/edit'),
        ];
    }
}
