<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Helper\InvoiceXmlGenerate;

class GenerateInvoiceXmlAction
{
    public function execute(Invoice $invoice, string $country = 'Romania'): string
    {
        return (new InvoiceXmlGenerate())->generate($invoice, $country);
    }
}
