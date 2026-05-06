<?php
namespace Modules\EInvoice\Services;
use Modules\EInvoice\Entities\Invoice;
class LateInvoiceFollowupService {
 public function generate(Invoice $invoice,string $tone='friendly',array $context=[]): string {
  $number=$invoice->invoice_number ?? $invoice->number ?? (string)($invoice->id ?? ''); $amount=$invoice->total ?? $invoice->amount ?? $invoice->balance_due ?? 'the outstanding balance';
  return match($tone){ 'direct'=>"Invoice {$number} is now overdue. Please arrange payment or contact us if anything needs correcting. Amount due: {$amount}.", 'firm'=>"Invoice {$number} remains unpaid. Please contact us today so we can resolve the balance or agree on next steps.", 'supportive'=>"We noticed invoice {$number} is still open. If paying in full is difficult, reply and we can discuss a payment plan.", 'escalation'=>"Invoice {$number} has reached escalation review. Please respond urgently to avoid further collection action.", default=>"A friendly reminder that invoice {$number} is due. Amount due: {$amount}. Thank you." };
 }
}
