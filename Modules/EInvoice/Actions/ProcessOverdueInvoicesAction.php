<?php
namespace Modules\EInvoice\Actions;
use Illuminate\Support\Collection;
use Modules\EInvoice\Services\LateInvoiceAutomationService;
class ProcessOverdueInvoicesAction { public function __construct(protected LateInvoiceAutomationService $automation) {} public function execute(iterable $invoices): Collection { return collect($invoices)->map(fn($invoice)=>$this->automation->runForInvoice($invoice))->filter()->values(); } }
