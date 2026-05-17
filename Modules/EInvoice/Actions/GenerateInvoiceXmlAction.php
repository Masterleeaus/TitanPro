<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoiceExported;
use Modules\EInvoice\Helper\InvoiceXmlGenerate;

class GenerateInvoiceXmlAction
{
    public function execute(Invoice $invoice, string $country = 'Romania'): string
    {
        $xml = (new InvoiceXmlGenerate())->generate($invoice, $country);

        event(new InvoiceExported($invoice, [
            'company_id'  => $invoice->company_id,
            'actor_id'    => null,
            'occurred_at' => now()->toIso8601String(),
            'country'     => $country,
        ]));

        return $xml;
    }
}
