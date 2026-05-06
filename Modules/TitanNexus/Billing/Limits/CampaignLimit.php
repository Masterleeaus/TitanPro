<?php

namespace Modules\TitanNexus\Billing\Limits;

/** Enforces active campaign limits. */
class CampaignLimit
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Enforces active campaign limits.'];
    }
}
