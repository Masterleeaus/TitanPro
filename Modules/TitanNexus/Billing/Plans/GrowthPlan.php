<?php
namespace Modules\TitanNexus\Billing\Plans;
final class GrowthPlan { public function limits(): array { return ['campaigns'=>25,'messages_per_month'=>10000,'ai_cards_per_month'=>1000]; } }

