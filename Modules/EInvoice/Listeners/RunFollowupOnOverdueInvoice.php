<?php
namespace Modules\EInvoice\Listeners;
use Modules\EInvoice\Events\InvoiceBecameOverdue;
use Modules\EInvoice\Services\LateInvoiceAutomationService;
class RunFollowupOnOverdueInvoice { public function __construct(protected LateInvoiceAutomationService $automation) {} public function handle(InvoiceBecameOverdue $event): void { $this->automation->runForInvoice($event->invoice,$event->context); } }
