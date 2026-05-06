<?php
namespace Modules\TitanNexus\DTOs;
class CampaignLaunchDTO { public function __construct(public int $campaignId, public array $targets = []) {} }
