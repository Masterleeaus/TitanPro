<?php
namespace Modules\Accountings\Listeners;
use Modules\Accountings\Services\LedgerSyncService;
use Modules\EInvoice\Events\InvoiceSent;
class PostAccountingOnInvoiceSent { public function __construct(protected LedgerSyncService $ledger) {} public function handle(InvoiceSent $event): void { $this->ledger->postInvoiceSent($event->invoice,$event->context); } }
