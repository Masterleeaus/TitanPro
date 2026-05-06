<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Integrations\ZeroPay\ZeroPayInvoiceHandoffPayload;

class PrepareZeroPayHandoffAction
{
    public function execute(Invoice $invoice, array $options = []): array
    {
        return ZeroPayInvoiceHandoffPayload::fromInvoice($invoice, $options)->toArray();
    }
}
