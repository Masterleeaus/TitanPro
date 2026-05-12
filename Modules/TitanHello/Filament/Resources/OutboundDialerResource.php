<?php

namespace Modules\TitanHello\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\TitanHello\Filament\Resources\OutboundDialerResource\Pages\ListOutboundDialers;
use Modules\TitanHello\Models\Call;

class OutboundDialerResource extends Resource
{
    protected static ?string $model = Call::class;

    protected static ?string $slug = 'titanhello/outbound-dialer';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-phone-arrow-up-right';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Hello';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Outbound Dialer';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('provider_call_sid')
                    ->label('Call SID')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('from_number')->label('From')->searchable(),
                Tables\Columns\TextColumn::make('to_number')->label('To')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Call $record): string => CallInboxResource::getUrl('view', ['record' => $record])),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('direction', 'outbound')
            ->when(
                auth()->user()?->organization_id,
                fn (Builder $query, int $organizationId): Builder => $query->where('company_id', $organizationId),
                fn (Builder $query): Builder => $query->whereRaw('1 = 0')
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOutboundDialers::route('/'),
        ];
    }
}
