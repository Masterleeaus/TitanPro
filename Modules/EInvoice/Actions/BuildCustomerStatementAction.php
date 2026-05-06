<?php
namespace Modules\EInvoice\Actions;
class BuildCustomerStatementAction { public function execute(int|string $customerId, iterable $invoices=[]): array { $rows=collect($invoices)->map(fn($invoice)=>['invoice_id'=>$invoice->id ?? null,'number'=>$invoice->invoice_number ?? $invoice->number ?? null,'date'=>$invoice->created_at ?? null,'due_date'=>$invoice->due_date ?? null,'balance'=>$invoice->balance_due ?? $invoice->total ?? 0,'status'=>$invoice->status ?? null])->values()->all(); return ['customer_id'=>$customerId,'rows'=>$rows,'total_due'=>array_sum(array_column($rows,'balance'))]; } }
