<?php
namespace Modules\TitanNexus\Http\Controllers;
final class AiCardController { public function execute(string $card): array { return ['card'=>$card,'mode'=>'draft_then_approve']; } }

