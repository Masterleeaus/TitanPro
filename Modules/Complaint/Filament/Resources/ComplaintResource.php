<?php

namespace Modules\Complaint\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                    ->action(fn (Complaint $record) => app(EscalateComplaintAction::class)->execute($record)),
                Tables\Actions\Action::make('resolve')
                    ->label('Resolve')
                    ->action(fn (Complaint $record) => app(ResolveComplaintAction::class)->execute($record)),
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
