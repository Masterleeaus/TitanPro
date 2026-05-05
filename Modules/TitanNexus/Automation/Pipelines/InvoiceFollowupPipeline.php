<?php
namespace Modules\TitanNexus\Automation\Pipelines;
class InvoiceFollowupPipeline { public function description(): string { return 'invoice overdue -> AI draft -> approval -> send -> metric update.'; } public function handle(array $payload = []): array { return ['handled'=>true,'payload'=>$payload]; } }
