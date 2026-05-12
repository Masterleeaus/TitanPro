<?php

namespace Modules\CRMCore\Filament\Resources;

use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\CRMCore\Actions\Lead\ConvertLeadAction;
use Modules\CRMCore\Actions\Lead\CreateLeadAction;
use Modules\CRMCore\Filament\Resources\LeadResource\Pages;
use Modules\CRMCore\Models\Lead;
use Modules\CRMCore\Models\LeadSource;
use Modules\CRMCore\Models\LeadStatus;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';
    protected static string|\UnitEnum|null $navigationGroup = 'CRM Core';
    protected static ?string $navigationLabel = 'Leads';
    protected static ?string $modelLabel = 'Lead';
    protected static ?string $pluralModelLabel = 'Leads';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 15;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Lead Details')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('contact_name')
                        ->label('Contact Name')
                        ->maxLength(255),
                    TextInput::make('contact_email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),
                    TextInput::make('contact_phone')
                        ->label('Phone')
                        ->tel()
                        ->maxLength(50),
                    TextInput::make('company_name')
                        ->label('Company Name')
                        ->maxLength(255),
                    Select::make('lead_status_id')
                        ->label('Status')
                        ->options(fn () => LeadStatus::query()->orderBy('position')->pluck('name', 'id')->all())
                        ->required(),
                    Select::make('lead_source_id')
                        ->label('Source')
                        ->options(fn () => LeadSource::query()->orderBy('name')->pluck('name', 'id')->all()),
                    TextInput::make('value')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    TextInput::make('crmcore_score')
                        ->label('Score')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100),
                    Select::make('assigned_to_user_id')
                        ->label('Assigned To')
                        ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    Textarea::make('description')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Lead::query())
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('contact_name')->label('Contact')->searchable()->toggleable(),
                TextColumn::make('contact_email')->label('Email')->searchable()->toggleable(),
                TextColumn::make('leadStatus.name')->label('Status')->badge()->sortable(),
                TextColumn::make('leadSource.name')->label('Source')->sortable()->toggleable(),
                TextColumn::make('value')->money('USD')->sortable(),
                TextColumn::make('crmcore_score')->label('Score')->numeric()->sortable()->toggleable(),
                TextColumn::make('assignedToUser.name')->label('Assigned')->toggleable(),
                TextColumn::make('converted_at')->label('Converted')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\Action::make('convert')
                    ->label('Convert')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Lead $record): bool => ! filled($record->converted_at))
                    ->action(fn (Lead $record) => app(ConvertLeadAction::class)->handle($record)),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit'   => Pages\EditLead::route('/{record}/edit'),
        ];
    }

    /**
     * Called by CreateLead page — delegates to the module Action class.
     *
     * @param array<string, mixed> $data
     */
    public static function createRecord(array $data): Lead
    {
        return app(CreateLeadAction::class)->handle($data);
    }
}

