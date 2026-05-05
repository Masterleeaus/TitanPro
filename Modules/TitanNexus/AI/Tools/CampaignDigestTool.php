<?php
namespace Modules\TitanNexus\AI\Tools;
class CampaignDigestTool { public function __invoke(array $input): array { return ['tool'=>'CampaignDigestTool','description'=>'Summarizes campaign performance and next actions.','approval_required'=>true,'result'=>$input]; } }
