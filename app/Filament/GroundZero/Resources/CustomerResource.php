<?php

namespace App\Filament\GroundZero\Resources;

use App\Filament\GroundZero\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Customers & Properties';

    protected static ?string $navigationLabel = 'Customers';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Customer Details')
                ->columns(['sm' => 1, 'lg' => 2])
                ->schema([
                    TextInput::make('first_name')->required()->maxLength(255),
                    TextInput::make('last_name')->required()->maxLength(255),
                    TextInput::make('email')->email()->maxLength(255),
                    TextInput::make('phone')->tel()->maxLength(50),
                    TextInput::make('mobile')->tel()->maxLength(50),
                    Textarea::make('notes')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('last_name')->label('Last Name')->searchable()->sortable(),
                TextColumn::make('first_name')->label('First Name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('properties_count')->label('Properties')->counts('properties'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('last_name');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit'   => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where('organization_id', $organizationId);
    }
}
