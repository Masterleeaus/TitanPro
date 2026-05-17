<?php

namespace Modules\Complaint\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Infolist;
use Modules\Complaint\Actions\EscalateComplaintAction;
use Modules\Complaint\Actions\ResolveComplaintAction;
use Modules\Complaint\Entities\Complaint;
use Modules\Complaint\Filament\Infolists\ComplaintInfolist;
use Modules\Complaint\Filament\Resources\ComplaintResource\Pages\ListComplaints;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-circle';
    protected static string|\UnitEnum|null $navigationGroup = 'ZeroFuss';
    protected static ?string $navigationLabel = 'Complaints';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('subject')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('priority')->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\Action::make('escalate')
                    ->label('Escalate')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('reason')
                            ->label('Escalation reason')
                            ->rows(3),
                    ])
                    ->action(fn (Complaint $record, array $data) => app(EscalateComplaintAction::class)->execute(
                        $record,
                        $data['reason'] ?? null
                    )),
                Tables\Actions\Action::make('resolve')
                    ->label('Resolve')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('resolution_outcome')
                            ->label('Resolution outcome')
                            ->rows(3),
                    ])
                    ->action(fn (Complaint $record, array $data) => app(ResolveComplaintAction::class)->execute(
                        $record,
                        ['resolution_outcome' => $data['resolution_outcome'] ?? null]
                    )),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ComplaintInfolist::make($infolist);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComplaints::route('/'),
        ];
    }
}
