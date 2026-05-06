<?php
namespace Modules\EInvoice\Services;
use Modules\EInvoice\Entities\Invoice;
class MailTemplateResolver {
 public function resolve(string $template, Invoice $invoice, array $data=[]): array {
  $vars=['invoice_number'=>$invoice->invoice_number ?? $invoice->number ?? $invoice->id ?? null,'amount_due'=>$invoice->balance_due ?? $invoice->total ?? null,'due_date'=>$invoice->due_date ?? null,'customer_name'=>$invoice->customer_name ?? data_get($invoice,'customer.name')];
  return ['template'=>$template,'subject'=>$this->subject($template,$vars),'variables'=>array_merge($vars,$data),'channel'=>'email'];
 }
 protected function subject(string $template,array $vars): string { return str_contains($template,'late_invoice') ? 'Reminder: invoice '.$vars['invoice_number'] : 'Invoice '.$vars['invoice_number']; }
}
