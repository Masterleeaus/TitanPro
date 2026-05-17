<?php

namespace Modules\Accountings\Actions;

use Illuminate\Support\Facades\Event;
use Modules\Accountings\Events\GstPosted;

class PostGstForInvoiceAction
{
    public function execute(object|array $invoice): array
    {
        $result = [
            'invoice_id' => data_get($invoice, 'id'),
            'gst_amount' => (float) data_get($invoice, 'tax_total', 0),
            'account' => 'gst_payable',
        ];

        Event::dispatch(new GstPosted([
            'company_id' => data_get($invoice, 'company_id', auth()->user()?->company_id),
            'actor_id' => auth()->id(),
            'occurred_at' => now()->toIso8601String(),
        ] + $result));

        return $result;
    }
}
