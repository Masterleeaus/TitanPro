<?php namespace Modules\CleaningJobs\Actions\AI; class GenerateChecklistFromKnowledgeAction { public function execute(array $payload): array { return ["ok"=>true,"payload"=>$payload]; } }
