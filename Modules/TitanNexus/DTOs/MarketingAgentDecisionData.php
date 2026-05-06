<?php
namespace Modules\TitanNexus\DTOs;
class MarketingAgentDecisionData { public function __construct(public mixed $tenant_id, public mixed $agent_action, public mixed $confidence, public mixed $requires_approval, public mixed $payload) {} }
