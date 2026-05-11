<?php

namespace App\Filament\TitanQuotes\Resources\CustomerResource\Pages;

use App\Filament\TitanQuotes\Resources\CustomerResource;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;
}
