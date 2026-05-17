<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PlatformPlanResource\Pages;
use App\Models\PlatformPlan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PlatformPlanResource extends Resource
{
    protected static ?string $model = PlatformPlan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform Commerce';

    protected static ?string $navigationLabel = 'Plans';

    protected static ?string $modelLabel = 'Plan';

    protected static ?string $pluralModelLabel = 'Plans';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Plan identity')
                ->columns(['sm' => 1, 'lg' => 2])
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('price')->numeric()->prefix('$')->required(),
                    Select::make('billing_interval')
                        ->options([
                            'days' => 'Days',
                            'monthly' => 'Monthly',
                            'yearly' => 'Yearly',
                        ])
                        ->required()
                        ->default('monthly'),
                    TextInput::make('interval_count')->numeric()->minValue(1)->default(1),
                    TextInput::make('trial_days')->numeric()->minValue(0)->default(0),
                    TextInput::make('currency')->maxLength(3)->default('AUD'),
                    Textarea::make('description')->columnSpanFull(),
                ]),
            Section::make('Entitlements')
                ->description('Converted from the Superadmin package module into a modern SaaS control surface.')
                ->columns(['sm' => 1, 'lg' => 4])
                ->schema([
                    TextInput::make('location_limit')->numeric()->helperText('Blank means unlimited'),
                    TextInput::make('user_limit')->numeric()->helperText('Blank means unlimited'),
                    TextInput::make('product_limit')->numeric()->helperText('Blank means unlimited'),
                    TextInput::make('invoice_limit')->numeric()->helperText('Blank means unlimited'),
                    TagsInput::make('features')->columnSpanFull(),
                    KeyValue::make('custom_permissions')->columnSpanFull(),
                ]),
            Section::make('Visibility')
                ->columns(['sm' => 1, 'lg' => 4])
                ->schema([
                    Toggle::make('is_active')->default(true),
                    Toggle::make('is_private')->default(false),
                    Toggle::make('is_popular')->default(false),
                    TextInput::make('sort_order')->numeric()->default(0),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('price')->money('AUD')->sortable(),
                TextColumn::make('billing_interval')->badge()->sortable(),
                TextColumn::make('trial_days')->label('Trial')->suffix(' days')->sortable(),
                IconColumn::make('is_active')->boolean()->label('Active'),
                IconColumn::make('is_private')->boolean()->label('Private'),
                IconColumn::make('is_popular')->boolean()->label('Popular'),
                TextColumn::make('sort_order')->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
                TernaryFilter::make('is_private'),
                TernaryFilter::make('is_popular'),
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
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlatformPlans::route('/'),
            'create' => Pages\CreatePlatformPlan::route('/create'),
            'edit' => Pages\EditPlatformPlan::route('/{record}/edit'),
        ];
    }
}
