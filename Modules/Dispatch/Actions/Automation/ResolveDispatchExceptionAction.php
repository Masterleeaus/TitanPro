<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Automation;

use Illuminate\Support\Carbon;
use Modules\Dispatch\Models\DispatchException;

class ResolveDispatchExceptionAction
{
    public function handle(DispatchException $exception, ?int $resolvedBy = null, ?string $note = null): DispatchException
    {
        $metadata = $exception->metadata ?? [];
        if ($note) {
            $metadata['resolution_note'] = $note;
        }

        $exception->forceFill([
            'status' => 'resolved',
            'resolved_by' => $resolvedBy,
            'resolved_at' => Carbon::now(),
            'metadata' => $metadata,
        ])->save();

        return $exception->refresh();
    }
}
