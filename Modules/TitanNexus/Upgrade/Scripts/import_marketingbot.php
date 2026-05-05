<?php

namespace Modules\TitanNexus\Upgrade\Scripts;

/** Imports MarketingBot tables and records. */
class import_marketingbot
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Imports MarketingBot tables and records.'];
    }
}
