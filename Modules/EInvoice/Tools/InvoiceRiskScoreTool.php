<?php
namespace Modules\EInvoice\Tools;
use Modules\EInvoice\Entities\Invoice;
class InvoiceRiskScoreTool { public string $name='score_invoice_risk'; public string $description='Scores late-payment risk from invoice age, balance, status, and follow-up stage.'; public function handle(Invoice $invoice): array { $daysLate=0; if($due=($invoice->due_date ?? null)){ $daysLate=max(0,now()->diffInDays($due,false)*-1); } $balance=(float)($invoice->balance_due ?? $invoice->total ?? 0); $score=min(100,(int)($daysLate*2+min(50,$balance/100))); return ['invoice_id'=>$invoice->id ?? null,'risk_score'=>$score,'days_late'=>$daysLate,'balance'=>$balance]; } }
