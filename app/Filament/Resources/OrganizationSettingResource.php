<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationSettingResource\Pages;
use App\Models\OrganizationSetting;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrganizationSettingResource extends Resource
{
    protected static ?string $model = OrganizationSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Organization Settings';

    protected static ?string $modelLabel = 'Organization Setting';

    protected static ?int $navigationSort = 910;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Organization Setting')
                ->columns(['sm' => 1, 'lg' => 2])
                ->schema([
                    TextInput::make('company_name')->label('Company Name')->maxLength(160),
                    TextInput::make('company_email')->label('Company Email')->maxLength(160),
                    TextInput::make('company_phone')->label('Company Phone')->maxLength(80),
                    TextInput::make('default_tax_rate')->label('Default Tax Rate')->numeric(),
                    TextInput::make('stripe_publishable_key')->label('Stripe Publishable Key')->maxLength(255),
                    TextInput::make('twilio_from_number')->label('Twilio From Number')->maxLength(80),
                    TextInput::make('sendgrid_from_email')->label('SendGrid From Email')->maxLength(160),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')->label('Company')->searchable()->sortable(),
                TextColumn::make('company_email')->label('Email')->searchable()->sortable(),
                TextColumn::make('default_tax_rate')->label('Tax Rate')->searchable()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Actions\EditAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizationSettings::route('/'),
            'create' => Pages\CreateOrganizationSetting::route('/create'),
            'edit' => Pages\EditOrganizationSetting::route('/{record}/edit'),
        ];
    }

    /**
     * Scope all queries to the current user's organization so that admins
     * can only see and edit their own organization's settings.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('organization_id', auth()->user()?->organization_id);
    }
}
