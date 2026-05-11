<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Workflow\Tools;

class RecordCreateTool extends BaseExternalTool
{
    public function key(): string
    {
        return 'record.create';
    }
}
