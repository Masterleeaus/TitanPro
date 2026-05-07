<?php

namespace App\Filament\ZeroPay\Resources\PaymentResource\Pages;

use App\Filament\ZeroPay\Resources\PaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
