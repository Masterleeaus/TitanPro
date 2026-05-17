<?php

namespace Modules\TitanGoField\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\TitanGoField\Models\FieldJob;

class FieldJobUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly FieldJob $fieldJob,
        public readonly int $actorId,
        public readonly array $changes = [],
    ) {}

    public function topic(): string
    {
        return 'titango_field.job.updated';
    }
}
