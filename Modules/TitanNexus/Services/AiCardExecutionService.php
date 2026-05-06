<?php
namespace Modules\TitanNexus\Services;
final class AiCardExecutionService { public function execute(string $card, array $context=[]): array { return ['card'=>$card,'context'=>$context]; } }

