<?php
namespace Modules\EInvoice\Services;
use Carbon\CarbonInterface;
use Modules\EInvoice\Entities\Invoice;
class LateInvoiceAutomationService {
 public function __construct(protected LateInvoiceFollowupService $followupService, protected MailTemplateResolver $templates, protected InvoiceLifecycleService $lifecycle) {}
 public function nextStageFor(Invoice $invoice, ?CarbonInterface $now=null): ?array {
  $now ??= now(); $dueAt=$invoice->due_date ?? $invoice->due_at ?? null; if(!$dueAt) return null;
  $daysLate=max(0,$now->diffInDays($dueAt,false)*-1); $selected=null;
  foreach(config('einvoice.late_invoice_ladder.stages', config('late_invoice_ladder.stages', [])) as $stage){ if($daysLate >= (int)$stage['day']) $selected=$stage; }
  return $selected;
 }
 public function runForInvoice(Invoice $invoice,array $context=[]): ?array {
  $stage=$this->nextStageFor($invoice); if(!$stage) return null;
  $message=$this->followupService->generate($invoice,$stage['tone'] ?? 'friendly',$context+['stage'=>$stage]);
  $template=$this->templates->resolve('late_invoice_'.$stage['code'],$invoice,['message'=>$message]);
  $this->lifecycle->transition($invoice,$this->stateForStage($stage['code']),['stage'=>$stage,'template'=>$template]);
  return ['stage'=>$stage,'message'=>$message,'template'=>$template];
 }
 protected function stateForStage(string $code): string { return match($code){ 'friendly_reminder'=>'escalation_stage_1','direct_reminder','call_script'=>'escalation_stage_2', default=>'escalation_stage_3' }; }
}
