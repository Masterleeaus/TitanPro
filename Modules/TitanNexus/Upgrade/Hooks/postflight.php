<?php

namespace Modules\TitanNexus\Upgrade\Hooks;

/** Registers module and warms indexes. */
class postflight
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Registers module and warms indexes.'];
    }
}
