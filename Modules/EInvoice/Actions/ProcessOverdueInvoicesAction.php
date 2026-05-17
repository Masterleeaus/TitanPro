<?php
namespace Modules\EInvoice\Actions;
use Illuminate\Support\Collection;
use Modules\EInvoice\Events\InvoiceBecameOverdue;
use Modules\EInvoice\Services\LateInvoiceAutomationService;
class ProcessOverdueInvoicesAction
{
    public function __construct(protected LateInvoiceAutomationService $automation) {}

    public function execute(iterable $invoices): Collection
    {
        return collect($invoices)->map(function ($invoice) {
            event(new InvoiceBecameOverdue($invoice, [
                'company_id'  => $invoice->company_id ?? null,
                'actor_id'    => null,
                'occurred_at' => now()->toIso8601String(),
            ]));

            return $this->automation->runForInvoice($invoice);
        })->filter()->values();
    }
}
