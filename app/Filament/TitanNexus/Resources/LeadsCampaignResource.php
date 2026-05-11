<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\LeadsCampaignResource\Pages;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\TitanLeads\Enums\CampaignStatus;
use Modules\TitanLeads\Enums\CampaignType;
use Modules\TitanLeads\Models\MarketingCampaign;

class LeadsCampaignResource extends Resource
{
    protected static ?string $model = MarketingCampaign::class;

    protected static ?string $slug = 'leads-campaigns';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Campaigns';

    protected static ?string $navigationGroup = 'Titan Leads';

    protected static ?int $navigationSort = 51;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Campaign Details')
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    Select::make('type')
                        ->options(array_column(CampaignType::cases(), 'value', 'value'))
                        ->required(),
                    Select::make('status')
                        ->options(array_column(CampaignStatus::cases(), 'value', 'value'))
                        ->default(CampaignStatus::pending->value)
                        ->required(),
                    DateTimePicker::make('scheduled_at'),
                    Textarea::make('content')->rows(4)->columnSpanFull(),
                    Textarea::make('instruction')->label('AI Instruction')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('type')->label('Channel')->badge()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('scheduled_at')->dateTime()->sortable(),
                TextColumn::make('started_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('finished_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->hasRole(['owner', 'admin']);
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDeleteAny(): bool
    {
        return static::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLeadsCampaigns::route('/'),
            'create' => Pages\CreateLeadsCampaign::route('/create'),
            'view'   => Pages\ViewLeadsCampaign::route('/{record}'),
            'edit'   => Pages\EditLeadsCampaign::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->id();

        if ($userId === null) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()->where('user_id', $userId);
    }
}
