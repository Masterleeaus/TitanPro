<?php
namespace Modules\Accountings\Tools;
class SuggestJournalCorrectionTool { public string $name='suggest_journal_correction'; public string $description='Suggests accounting corrections for invoice, GST, receivable, or write-off mismatch.'; public function handle(array $context): array { return ['suggestion'=>'Review receivable, revenue, and GST payable lines against the invoice totals.','context'=>$context,'requires_accountant_approval'=>true]; } }
