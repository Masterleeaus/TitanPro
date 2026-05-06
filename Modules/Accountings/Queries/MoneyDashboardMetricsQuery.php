<?php
namespace Modules\Accountings\Queries;
use Modules\Accountings\Services\ReceivableAgingService;
use Modules\Accountings\Services\GstPostingService;
class MoneyDashboardMetricsQuery { public function __construct(protected ReceivableAgingService $aging, protected GstPostingService $gst) {} public function handle(iterable $invoices=[]): array { $invoices=collect($invoices); $aging=$this->aging->buckets($invoices); $outstanding=array_sum($aging); return ['outstanding_receivables'=>round($outstanding,2),'aging'=>$aging,'gst'=>$this->gst->snapshot($invoices),'overdue_risk_score'=>$outstanding>0?min(100,(int)(($aging['90_plus'] ?? 0)/$outstanding*100)):0,'write_off_exposure'=>round(($aging['90_plus'] ?? 0)*0.35,2)]; } }
