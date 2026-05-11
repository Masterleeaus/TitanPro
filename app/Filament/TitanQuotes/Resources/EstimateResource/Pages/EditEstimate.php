<?php

namespace App\Filament\TitanQuotes\Resources\EstimateResource\Pages;

use App\Filament\TitanQuotes\Resources\EstimateResource;
use App\Models\Estimate;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditEstimate extends EditRecord
{
    protected static string $resource = EstimateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sendQuote')
                ->label('Send Quote')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === Estimate::STATUS_DRAFT)
                ->action(function (): void {
                    $this->record->update([
                        'status' => Estimate::STATUS_SENT,
                        'sent_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Quote sent.')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'sent_at']);
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
