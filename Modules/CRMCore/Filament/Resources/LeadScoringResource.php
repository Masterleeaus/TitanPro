<?php

namespace Modules\CRMCore\Filament\Resources;

use App\Models\Customer;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages;
use Modules\CRMCore\Models\Lead;
use Modules\CRMCore\Models\LeadSource;
use Modules\CRMCore\Models\LeadStatus;

class LeadScoringResource extends Resource
{
    protected static ?string $model = Lead::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';
    protected static string|\UnitEnum|null $navigationGroup = 'CRM Core';
    protected static ?string $navigationLabel = 'Leads';
    protected static ?string $modelLabel = 'Lead';
    protected static ?string $pluralModelLabel = 'Leads';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Lead Details')
                ->columns(1)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Select::make('crmcore_customer_id')
                        ->label('Existing Customer')
                        ->options(fn () => Customer::query()
                            ->orderBy('first_name')
                            ->orderBy('last_name')
                            ->limit(100)
                            ->get()
                            ->mapWithKeys(fn ($customer) => [$customer->getKey() => trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) . ($customer->email ? ' — ' . $customer->email : '')])
                            ->filter(fn ($label) => filled(trim((string) $label)))
                            ->all())
                        ->searchable()
                        ->preload(),
                    TextInput::make('value')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    TextInput::make('contact_name')
                        ->maxLength(255),
                    TextInput::make('contact_email')
                        ->email()
                        ->maxLength(255),
                    TextInput::make('contact_phone')
                        ->tel()
                        ->maxLength(255),
                    TextInput::make('company_name')
                        ->maxLength(255),
                    Select::make('lead_status_id')
                        ->label('Status')
                        ->options(fn () => static::leadStatusOptions())
                        ->default(fn () => static::defaultLeadStatusId())
                        ->required(),
                    Select::make('lead_source_id')
                        ->label('Source')
                        ->options(fn () => static::leadSourceOptions())
                        ->default(fn () => static::defaultLeadSourceId()),
                    Select::make('assigned_to_user_id')
                        ->label('Assigned To')
                        ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    TextInput::make('crmcore_score')
                        ->label('Score')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100),
                    Textarea::make('description')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('crmcoreCustomer.full_name')->label('Customer')->searchable(['first_name', 'last_name'])->toggleable(),
                TextColumn::make('contact_name')->searchable(),
                TextColumn::make('contact_email')->searchable(),
                TextColumn::make('company_name')->searchable()->toggleable(),
                TextColumn::make('leadStatus.name')->label('Status')->badge()->sortable(),
                TextColumn::make('value')->money('USD')->sortable(),
                TextColumn::make('crmcore_score')->label('Score')->numeric()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\Action::make('score')
                    ->label('Score Lead')
                    ->icon('heroicon-o-bolt')
                    ->action(fn ($record) => app(\Modules\CRMCore\Actions\ScoreLead::class)->handle($record)),
                Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeadScorings::route('/'),
            'create' => Pages\CreateLeadScoring::route('/create'),
            'edit' => Pages\EditLeadScoring::route('/{record}/edit'),
        ];
    }

    public static function defaultLeadStatusId(): ?int
    {
        return LeadStatus::query()->where('is_default', true)->value('id')
            ?? LeadStatus::query()->orderBy('position')->value('id')
            ?? LeadStatus::query()->create([
                'name' => 'New',
                'color' => '#3b82f6',
                'position' => 1,
                'is_default' => true,
                'is_final' => false,
            ])->getKey();
    }

    public static function leadStatusOptions(): array
    {
        static::defaultLeadStatusId();

        return LeadStatus::query()->orderBy('position')->orderBy('name')->pluck('name', 'id')->all();
    }

    public static function defaultLeadSourceId(): ?int
    {
        return LeadSource::query()->orderBy('name')->value('id')
            ?? LeadSource::query()->create(['name' => 'Website'])->getKey();
    }

    public static function leadSourceOptions(): array
    {
        static::defaultLeadSourceId();

        return LeadSource::query()->orderBy('name')->pluck('name', 'id')->all();
    }
}
