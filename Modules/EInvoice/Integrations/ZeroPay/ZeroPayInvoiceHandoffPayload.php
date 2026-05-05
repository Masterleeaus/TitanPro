<?php

namespace Modules\EInvoice\Integrations\ZeroPay;

use Modules\EInvoice\Entities\Invoice;

class ZeroPayInvoiceHandoffPayload
{
    public function __construct(private readonly Invoice $invoice, private readonly array $options = []) {}

    public static function fromInvoice(Invoice $invoice, array $options = []): self
    {
        return new self($invoice, $options);
    }

    public function toArray(): array
    {
        return [
            'source' => 'titan_money',
            'type' => 'invoice_handoff',
            'invoice_id' => $this->invoice->getKey(),
            'invoice_number' => $this->invoice->invoice_number ?? $this->invoice->number ?? null,
            'company_id' => $this->invoice->company_id ?? null,
            'customer_id' => $this->invoice->customer_id ?? null,
            'amount_due' => $this->invoice->amount_due ?? $this->invoice->total ?? $this->invoice->amount ?? null,
            'currency' => $this->invoice->currency ?? ($this->options['currency'] ?? 'AUD'),
            'due_date' => $this->invoice->due_date ?? null,
            'status' => $this->invoice->status ?? null,
            'return_url' => $this->options['return_url'] ?? null,
            'metadata' => $this->options['metadata'] ?? [],
            'payment_owner' => 'external_zeropay_system',
        ];
    }
}
