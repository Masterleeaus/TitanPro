<?php

namespace Modules\TitanNexus\Billing\Meters;

/** Tracks message usage. */
class MessagesSentMeter
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Tracks message usage.'];
    }
}
