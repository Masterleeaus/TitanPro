<?php

namespace Modules\TitanNexus\Events;

class LegacyCodeMerged
{
    public function __construct(public array $summary) {}
}
