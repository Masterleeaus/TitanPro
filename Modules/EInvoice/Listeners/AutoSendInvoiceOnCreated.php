<?php
namespace Modules\EInvoice\Listeners;
use Modules\EInvoice\Actions\SendInvoiceAction;
use Modules\EInvoice\Events\InvoiceCreated;
class AutoSendInvoiceOnCreated { public function __construct(protected SendInvoiceAction $sendInvoice) {} public function handle(InvoiceCreated $event): void { if(config('einvoice.invoice_lifecycle.auto_send.enabled', false)) $this->sendInvoice->execute($event->invoice,['source'=>'auto_send_rule']); } }
