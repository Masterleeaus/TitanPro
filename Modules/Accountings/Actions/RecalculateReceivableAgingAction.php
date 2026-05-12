<?php
namespace Modules\Accountings\Actions;
use Illuminate\Support\Facades\Event;
use Modules\Accountings\Events\ReceivablesAged;
use Modules\Accountings\Services\ReceivableAgingService;
class RecalculateReceivableAgingAction { public function __construct(protected ReceivableAgingService $aging) {} public function execute(iterable $invoices): array { $result=$this->aging->buckets($invoices); Event::dispatch(new ReceivablesAged(['company_id'=>auth()->user()?->company_id,'actor_id'=>auth()->id(),'occurred_at'=>now()->toIso8601String(),'buckets'=>$result])); return $result; } }
