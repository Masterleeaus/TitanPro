<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PlatformCouponResource\Pages;
use App\Models\PlatformCoupon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlatformCouponResource extends Resource
{
    protected static ?string $model = PlatformCoupon::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform Commerce';

    protected static ?string $navigationLabel = 'Coupons';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Coupon')
                ->columns(['sm' => 1, 'lg' => 2])
                ->schema([
                    TextInput::make('code')->required()->maxLength(100)->unique(ignoreRecord: true),
                    Select::make('discount_type')
                        ->options(['percentage' => 'Percentage', 'fixed' => 'Fixed amount'])
                        ->default('percentage')
                        ->required(),
                    TextInput::make('discount_value')->numeric()->required(),
                    TextInput::make('usage_limit')->numeric(),
                    DateTimePicker::make('starts_at'),
                    DateTimePicker::make('expires_at'),
                    TagsInput::make('applies_to_plans')->columnSpanFull(),
                    Toggle::make('is_active')->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable()->copyable(),
                TextColumn::make('discount_type')->badge(),
                TextColumn::make('discount_value')->sortable(),
                TextColumn::make('used_count')->sortable(),
                TextColumn::make('usage_limit')->sortable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('expires_at')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlatformCoupons::route('/'),
            'create' => Pages\CreatePlatformCoupon::route('/create'),
            'edit' => Pages\EditPlatformCoupon::route('/{record}/edit'),
        ];
    }
}
