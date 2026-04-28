<?php

namespace Modules\CRMCore\ValueObjects;

class PipelineScore
{
    public function __construct(public int $value)
    {
        $this->value = max(0, min(100, $value));
    }

    public function isHot(): bool
    {
        return $this->value >= 75;
    }
}
