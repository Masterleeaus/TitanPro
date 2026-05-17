<?php

namespace Modules\ExampleModule\UI\Cards;

class UnifiedTablesCard
{
    public function tabs(): array
    {
        return config('example-module.tables', []);
    }
}
