<?php
namespace Modules\Accountings\Actions;
use Illuminate\Support\Facades\Event;
use Modules\Accountings\Events\StatementGenerated;
use Modules\Accountings\Services\StatementExportService;
class GenerateAccountantExportPackAction { public function __construct(protected StatementExportService $exports) {} public function execute(array $data=[]): array { $result=$this->exports->accountantPack($data); Event::dispatch(new StatementGenerated(['company_id'=>$data['company_id'] ?? auth()->user()?->company_id,'actor_id'=>$data['actor_id'] ?? auth()->id(),'occurred_at'=>now()->toIso8601String(),'generated_at'=>$result['generated_at'] ?? now()->toIso8601String()])); return $result; } }
