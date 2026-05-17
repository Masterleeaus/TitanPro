<?php

namespace Modules\TitanRewind\Events;

use Modules\TitanRewind\Models\RewindFix;

class RewindApplied
{
    public function __construct(public RewindFix $fix, public array $actor = []) {}
}
