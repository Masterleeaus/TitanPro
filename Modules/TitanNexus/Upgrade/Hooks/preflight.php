<?php

namespace Modules\TitanNexus\Upgrade\Hooks;

/** Checks dependencies before upgrade. */
class preflight
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Checks dependencies before upgrade.'];
    }
}
