<?php

namespace Modules\ExampleModule\UI\ZeroChat\ToolBlocks;

class ToolBlockRegistry
{
    public function blocks(): array
    {
        return ['card', 'table', 'form', 'timeline', 'citation', 'action', 'streaming_status'];
    }
}
