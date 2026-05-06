<?php
namespace Modules\EInvoice\Actions;
use Modules\EInvoice\Entities\Invoice;
class SuggestPaymentPlanAction { public function execute(Invoice $invoice,int $instalments=3): array { $balance=(float)($invoice->balance_due ?? $invoice->total ?? 0); $instalments=max(1,min(12,$instalments)); return ['invoice_id'=>$invoice->id ?? null,'instalments'=>$instalments,'amount_each'=>round($balance/$instalments,2),'note'=>'Suggested only. Payment execution remains in ZeroPay.']; } }
