<?php

namespace Modules\ZeroPayModule\Filament\Resources\ZeroPayBankDepositResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayBankDepositResource;

class ListZeroPayBankDeposits extends ListRecords
{
    protected static string $resource = ZeroPayBankDepositResource::class;

    public function getTabs(): array
    {
        return [
            'pending_review' => Tab::make('Pending Review')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending_review')),
            'matched' => Tab::make('Matched')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'matched')),
            'unmatched' => Tab::make('Unmatched')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'unmatched')),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'pending_review';
    }
}
