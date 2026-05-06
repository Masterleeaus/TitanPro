<?php
namespace Modules\CleaningJobs\Services\AI;
class AgentGuardrailService { public function check(array $message, array $context = [], array $sources = []): array { return ["status"=>"checked","requires_human_approval"=>false]; } }
