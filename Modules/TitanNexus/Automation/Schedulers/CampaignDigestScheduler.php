<?php
namespace Modules\TitanNexus\Automation\Schedulers;
class CampaignDigestScheduler { public function description(): string { return 'Schedules campaign digest generation.'; } public function handle(array $payload = []): array { return ['handled'=>true,'payload'=>$payload]; } }
