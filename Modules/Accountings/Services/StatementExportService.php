<?php
namespace Modules\Accountings\Services;
class StatementExportService { public function accountantPack(array $data=[]): array { return ['journals'=>$data['journals'] ?? [],'tax_summary'=>$data['tax_summary'] ?? [],'trial_balance'=>$data['trial_balance'] ?? [],'receivables_aging'=>$data['receivables_aging'] ?? [],'generated_at'=>now()->toIso8601String()]; } }
