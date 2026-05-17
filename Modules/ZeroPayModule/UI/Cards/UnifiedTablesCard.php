<?php

namespace Modules\ZeroPayModule\UI\Cards;

class UnifiedTablesCard
{
    public function tabs(): array
    {
        return config('zeropay-module.tables', []);
    }
}
