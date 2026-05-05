<?php
namespace Modules\TitanNexus\API\Transformers;
final class CampaignTransformer { public function transform(array $campaign): array { return $campaign + ['module'=>'TitanNexus']; } }

