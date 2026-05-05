<?php
namespace Modules\EInvoice\UI\Widgets;
class ReceivablesIntelligenceWidget { public function cards(array $metrics): array { return [['label'=>'Outstanding Receivables','value'=>$metrics['outstanding_receivables'] ?? 0],['label'=>'Overdue Risk Score','value'=>($metrics['overdue_risk_score'] ?? 0).'%'],['label'=>'GST Liability Snapshot','value'=>data_get($metrics,'gst.gst_payable_estimate',0)],['label'=>'Write-off Exposure','value'=>$metrics['write_off_exposure'] ?? 0]]; } }
