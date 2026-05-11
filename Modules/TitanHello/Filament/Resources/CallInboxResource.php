<?php

namespace Modules\TitanHello\Filament\Resources;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\TitanHello\Filament\Resources\CallInboxResource\Pages\ListCallInboxes;
use Modules\TitanHello\Filament\Resources\CallInboxResource\Pages\ViewCallInbox;
use Modules\TitanHello\Models\Call;

class CallInboxResource extends Resource
{
    protected static ?string $model = Call::class;

    protected static ?string $slug = 'titanhello/call-inbox';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-phone';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Hello';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Call Inbox';

    protected static ?string $modelLabel = 'Call';

    protected static ?string $pluralModelLabel = 'Call Inbox';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('provider_call_sid')
                    ->label('Call SID')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('direction')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('from_number')
                    ->label('From')
                    ->searchable(),
                Tables\Columns\TextColumn::make('to_number')
                    ->label('To')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_seconds')
                    ->label('Duration (s)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('latest_recording_url')
                    ->label('Recording')
                    ->getStateUsing(fn (Call $record): ?string => $record->recordings()->latest('id')->value('recording_url'))
                    ->url(fn (?string $state): ?string => $state, shouldOpenInNewTab: true)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('direction')
                    ->options([
                        'inbound' => 'Inbound',
                        'outbound' => 'Outbound',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'ringing' => 'Ringing',
                        'queued' => 'Queued',
                        'dialing' => 'Dialing',
                        'in-progress' => 'In Progress',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'no-answer' => 'No Answer',
                        'busy' => 'Busy',
                        'escalated' => 'Escalated',
                    ]),
            ])
            ->recordActions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Call Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('provider_call_sid')->label('Call SID'),
                    TextEntry::make('provider')->badge(),
                    TextEntry::make('direction')->badge(),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('from_number')->label('From'),
                    TextEntry::make('to_number')->label('To'),
                    TextEntry::make('duration_seconds')->label('Duration (seconds)'),
                    TextEntry::make('call_outcome')->label('Outcome')->placeholder('—'),
                ]),
            Section::make('Recording')
                ->schema([
                    TextEntry::make('latest_recording_url')
                        ->label('Latest recording')
                        ->state(fn (Call $record): ?string => $record->recordings()->latest('id')->value('recording_url'))
                        ->url(fn (?string $state): ?string => $state, shouldOpenInNewTab: true)
                        ->placeholder('No recording'),
                ]),
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('company_id', $organizationId);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCallInboxes::route('/'),
            'view' => ViewCallInbox::route('/{record}'),
        ];
    }
}
