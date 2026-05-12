<?php
namespace Modules\Accountings\Tools;
use Illuminate\Support\Facades\Event;
use Modules\Accountings\Events\LedgerAdjustmentSuggested;
class SuggestJournalCorrectionTool { public string $name='suggest_journal_correction'; public string $description='Suggests accounting corrections for invoice, GST, receivable, or write-off mismatch.'; public function handle(array $context): array { $result=['suggestion'=>'Review receivable, revenue, and GST payable lines against the invoice totals.','context'=>$context,'requires_accountant_approval'=>true]; Event::dispatch(new LedgerAdjustmentSuggested(['company_id'=>$context['company_id'] ?? auth()->user()?->company_id,'actor_id'=>$context['actor_id'] ?? auth()->id(),'occurred_at'=>now()->toIso8601String()] + $result)); return $result; } }
