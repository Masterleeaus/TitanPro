<?php

declare(strict_types=1);

namespace Modules\Dispatch\Events\Domain;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Dispatch\Models\AssignShift;

class DispatchStatusChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public AssignShift $assignment,
        public string $fromStatus,
        public string $toStatus,
        public ?string $notes = null,
    ) {}
}
