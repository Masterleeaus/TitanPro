<?php

namespace Modules\EInvoice\Events;

use Modules\EInvoice\Entities\Invoice;

class ZeroPayHandoffInitiated
{
    public readonly int $companyId;
    public readonly ?int $actorId;
    public readonly string $occurredAt;

    public function __construct(public Invoice $invoice, public array $context = [])
    {
        $this->companyId  = (int) ($context['company_id'] ?? $invoice->company_id ?? 0);
        $this->actorId    = isset($context['actor_id']) ? (int) $context['actor_id'] : null;
        $this->occurredAt = $context['occurred_at'] ?? now()->toIso8601String();
    }
}
