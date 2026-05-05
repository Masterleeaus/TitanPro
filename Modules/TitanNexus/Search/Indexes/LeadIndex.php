<?php

namespace Modules\TitanNexus\Search\Indexes;

/** Indexes leads. */
class LeadIndex
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Indexes leads.'];
    }
}
