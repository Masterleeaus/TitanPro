<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;

class CreateInvoiceAction
{
    public function execute(array $data): Invoice
    {
        return Invoice::create($data);
    }
}
