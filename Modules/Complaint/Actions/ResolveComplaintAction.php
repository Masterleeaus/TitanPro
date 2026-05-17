<?php

namespace Modules\Complaint\Actions;

use Illuminate\Support\Facades\Log;
use Modules\Complaint\Entities\Complaint;
use Modules\Complaint\Events\ComplaintResolved;
use Modules\Complaint\Support\Enums\ComplaintStatus;

class ResolveComplaintAction
{
    /**
     * @param array{resolution_outcome?: string} $data
     */
    public function execute(Complaint $complaint, array $data = []): Complaint
    {
        $complaint->status = ComplaintStatus::RESOLVED->value;
        $complaint->resolved_at = now();

        if (! empty($data['resolution_outcome'])) {
            $complaint->resolution_outcome = (string) $data['resolution_outcome'];
        }

        $complaint->save();

        ComplaintResolved::dispatch($complaint);
        $this->emitCustomerNotificationSignal($complaint);

        return $complaint;
    }

    private function emitCustomerNotificationSignal(Complaint $complaint): void
    {
        if (! class_exists(\App\Extensions\TitanPulse\Services\SignalBus\SignalEmitter::class)) {
            return;
        }

        if (! $complaint->company_id || ! $complaint->id) {
            return;
        }

        try {
            \App\Extensions\TitanPulse\Services\SignalBus\SignalEmitter::emit(
                'ZeroFussPortal.NotifyCustomer',
                'complaint',
                $complaint->id,
                [
                    'complaint_id' => $complaint->id,
                    'status' => $complaint->status,
                    'resolution_outcome' => $complaint->resolution_outcome,
                ],
                [
                    'company_id' => $complaint->company_id,
                    'team_id' => $complaint->company_id,
                    'source' => 'complaint.resolve',
                ]
            );
        } catch (\Throwable $e) {
            Log::debug('Failed to emit complaint resolved signal.', [
                'complaint_id' => $complaint->id,
                'company_id' => $complaint->company_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
