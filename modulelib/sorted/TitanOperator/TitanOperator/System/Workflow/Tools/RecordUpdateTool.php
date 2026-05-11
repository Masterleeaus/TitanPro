<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Workflow\Tools;

class RecordUpdateTool extends BaseExternalTool
{
    public function key(): string
    {
        return 'record.update';
    }
}
