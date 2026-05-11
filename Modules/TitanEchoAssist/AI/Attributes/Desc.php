<?php

namespace Modules\TitanEchoAssist\AI\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Desc
{
    public function __construct(
        public readonly string $description,
        public readonly bool $required = true,
    ) {}
}
