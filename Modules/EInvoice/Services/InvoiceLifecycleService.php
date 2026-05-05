<?php
namespace Modules\EInvoice\Services;
use Illuminate\Support\Facades\Event;
use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoiceBecameOverdue;
use Modules\EInvoice\Events\InvoiceClosed;
use Modules\EInvoice\Events\InvoiceCreated;
use Modules\EInvoice\Events\InvoiceEscalated;
use Modules\EInvoice\Events\InvoiceSent;
use Modules\EInvoice\Events\InvoiceViewed;
class InvoiceLifecycleService {
 public function transition(Invoice $invoice, string $state, array $context=[]): Invoice {
  if (method_exists($invoice,'forceFill')) { $invoice->forceFill(['status'=>$state]); if (method_exists($invoice,'save')) $invoice->save(); } else { $invoice->status=$state; }
  Event::dispatch(match($state){
   'sent'=>new InvoiceSent($invoice,$context), 'viewed'=>new InvoiceViewed($invoice,$context), 'overdue'=>new InvoiceBecameOverdue($invoice,$context),
   'escalation_stage_1','escalation_stage_2','escalation_stage_3'=>new InvoiceEscalated($invoice,$context+['stage'=>$state]), 'closed'=>new InvoiceClosed($invoice,$context),
   default=>new InvoiceCreated($invoice,$context+['state'=>$state]),
  });
  return $invoice;
 }
 public function markViewed(Invoice $invoice,array $context=[]): Invoice { return $this->transition($invoice,'viewed',$context); }
 public function markOverdue(Invoice $invoice,array $context=[]): Invoice { return $this->transition($invoice,'overdue',$context); }
 public function closeFromZeroPaySignal(Invoice $invoice,array $signal): Invoice { return $this->transition($invoice,'closed',['source'=>'zeropay_signal','signal'=>$signal]); }
}
