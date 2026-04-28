<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-plus-circle';
    protected static string|\UnitEnum|null $navigationGroup = 'Jobs';
    protected static ?string $navigationLabel = 'Add-ons';
    protected static ?string $modelLabel = 'Add-on';
    protected static ?string $pluralModelLabel = 'Add-ons';
    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Add-on Details')->columnSpanFull()->columns(1)->schema([
                TextInput::make('name')->label('Add-on Name')->required()->maxLength(255),
                TextInput::make('sku')->label('SKU')->maxLength(100),
                Select::make('category')->options([
                    'cleaning' => 'Cleaning', 'supplies' => 'Supplies', 'equipment' => 'Equipment', 'fee' => 'Fee', 'discount' => 'Discount', 'other' => 'Other',
                ])->default('cleaning'),
                Select::make('pricing_type')->label('Pricing Type')->options([
                    'fixed' => 'Fixed price', 'per_room' => 'Per room', 'per_bathroom' => 'Per bathroom', 'per_hour' => 'Per hour', 'per_sqm' => 'Per m²', 'quantity' => 'Quantity based',
                ])->default('fixed')->required(),
                TextInput::make('unit_price')->label('Base Price')->numeric()->prefix('$')->minValue(0)->step(0.01)->required(),
                Select::make('unit')->options([
                    'each' => 'Each', 'room' => 'Room', 'bathroom' => 'Bathroom', 'hr' => 'Hour', 'sqm' => 'm²', 'visit' => 'Visit',
                ])->default('each')->required(),
                TextInput::make('estimated_minutes')->label('Estimated Minutes')->numeric()->minValue(0),
                Toggle::make('is_upsell')->label('Show as Upsell')->default(true),
                Toggle::make('injects_checklist_tasks')->label('Adds Checklist Tasks')->default(false),
                Textarea::make('description')->label('Description / Cleaner Notes')->rows(3)->columnSpanFull(),
                Toggle::make('is_taxable')->label('Taxable')->default(true),
                Toggle::make('is_active')->label('Active')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Add-on')->searchable()->sortable(),
                TextColumn::make('category')->badge()->sortable(),
                TextColumn::make('pricing_type')->label('Pricing')->badge()->formatStateUsing(fn (?string $state): string => match ($state) {
                    'per_room' => 'Per room', 'per_bathroom' => 'Per bathroom', 'per_hour' => 'Per hour', 'per_sqm' => 'Per m²', 'quantity' => 'Quantity', default => 'Fixed',
                }),
                TextColumn::make('unit_price')->label('Price')->money('USD')->sortable(),
                TextColumn::make('unit')->sortable(),
                TextColumn::make('estimated_minutes')->label('Mins')->sortable()->toggleable(),
                IconColumn::make('is_upsell')->label('Upsell')->boolean(),
                IconColumn::make('is_active')->label('Active')->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')->options(['cleaning' => 'Cleaning', 'supplies' => 'Supplies', 'equipment' => 'Equipment', 'fee' => 'Fee', 'discount' => 'Discount', 'other' => 'Other']),
                TernaryFilter::make('is_active')->label('Active'),
                TrashedFilter::make(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListItems::route('/'), 'create' => Pages\CreateItem::route('/create'), 'edit' => Pages\EditItem::route('/{record}/edit')];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class])->where('organization_id', auth()->user()?->organization_id);
    }
}
