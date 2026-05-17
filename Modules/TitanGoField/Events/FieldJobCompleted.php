<?php

namespace Modules\TitanGoField\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\TitanGoField\Models\FieldJob;

class FieldJobCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly FieldJob $fieldJob,
        public readonly int $actorId,
    ) {}

    public function topic(): string
    {
        return 'titango_field.job.completed';
    }
}
