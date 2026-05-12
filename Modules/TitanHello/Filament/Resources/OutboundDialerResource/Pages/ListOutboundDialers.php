<?php

namespace Modules\TitanHello\Filament\Resources\OutboundDialerResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Modules\TitanHello\Filament\Resources\OutboundDialerResource;
use Modules\TitanHello\Services\Calls\OutboundCallService;

class ListOutboundDialers extends ListRecords
{
    protected static string $resource = OutboundDialerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('dial')
                ->label('Dial Number')
                ->icon('heroicon-o-phone')
                ->form([
                    TextInput::make('to_number')
                        ->label('To')
                        ->required()
                        ->tel()
                        ->maxLength(64),
                    TextInput::make('from_number')
                        ->label('From')
                        ->tel()
                        ->maxLength(64),
                ])
                ->action(function (array $data): void {
                    $call = app(OutboundCallService::class)->dialNumber(
                        auth()->user()?->organization_id ?? 0,
                        $data['from_number'] ?? null,
                        (string) $data['to_number'],
                        auth()->user(),
                        ['source' => 'filament_outbound_dialer']
                    );

                    Notification::make()
                        ->title('Outbound call queued')
                        ->body('Call #'.$call->id.' is now '.$call->status.'.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
