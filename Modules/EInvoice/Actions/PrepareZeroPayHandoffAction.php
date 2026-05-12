<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\ZeroPayHandoffInitiated;
use Modules\EInvoice\Integrations\ZeroPay\ZeroPayInvoiceHandoffPayload;

class PrepareZeroPayHandoffAction
{
    public function execute(Invoice $invoice, array $options = []): array
    {
        $payload = ZeroPayInvoiceHandoffPayload::fromInvoice($invoice, $options)->toArray();

        event(new ZeroPayHandoffInitiated($invoice, [
            'company_id'  => $invoice->company_id,
            'actor_id'    => $options['actor_id'] ?? null,
            'occurred_at' => now()->toIso8601String(),
        ]));

        return $payload;
    }
}
