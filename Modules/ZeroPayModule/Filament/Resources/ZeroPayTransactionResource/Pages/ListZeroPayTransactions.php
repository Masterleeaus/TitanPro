<?php

namespace Modules\ZeroPayModule\Filament\Resources\ZeroPayTransactionResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayTransactionResource;

class ListZeroPayTransactions extends ListRecords
{
    protected static string $resource = ZeroPayTransactionResource::class;
}
