<?php
namespace Modules\Accountings\Services;
class ReceivableAgingService { public function buckets(iterable $invoices): array { $b=['0_30'=>0.0,'31_60'=>0.0,'61_90'=>0.0,'90_plus'=>0.0]; foreach($invoices as $invoice){ $due=data_get($invoice,'due_date'); $days=$due ? max(0, now()->diffInDays($due,false)*-1) : 0; $amount=(float)data_get($invoice,'balance_due',data_get($invoice,'total',0)); $key=$days<=30?'0_30':($days<=60?'31_60':($days<=90?'61_90':'90_plus')); $b[$key]+=$amount; } return array_map(fn($v)=>round($v,2),$b); } }
