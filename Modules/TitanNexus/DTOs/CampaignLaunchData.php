<?php
namespace Modules\TitanNexus\DTOs; final readonly class CampaignLaunchData { public function __construct(public array $payload = []) {} public function toArray(): array { return $this->payload; } }
