<?php
namespace Modules\TitanNexus\Automation\Handlers;
class LateInvoiceFollowupHandler { public function description(): string { return 'Queues AI-generated payment reminder drafts.'; } public function handle(array $payload = []): array { return ['handled'=>true,'payload'=>$payload]; } }
