<?php
namespace Modules\Accountings\Tools;
class ForecastReceivableVelocityTool { public string $name='forecast_receivable_velocity'; public string $description='Estimates short-term collection velocity from invoice balances and age buckets.'; public function handle(array $agingBuckets,int $days=30): array { $total=array_sum($agingBuckets); $expected=($agingBuckets['0_30'] ?? 0)*0.75+($agingBuckets['31_60'] ?? 0)*0.45+($agingBuckets['61_90'] ?? 0)*0.25+($agingBuckets['90_plus'] ?? 0)*0.1; return ['days'=>$days,'outstanding'=>round($total,2),'expected_collected'=>round($expected,2)]; } }
