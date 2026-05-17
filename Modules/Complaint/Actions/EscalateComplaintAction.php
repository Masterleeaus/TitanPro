<?php

namespace Modules\Complaint\Actions;

use Modules\Complaint\Entities\Complaint;
use Modules\Complaint\Entities\ComplaintReply;
use Modules\Complaint\Events\ComplaintEscalated;
use Modules\Complaint\Support\Enums\ComplaintSeverity;
use Modules\Complaint\Support\Enums\ComplaintStatus;

class EscalateComplaintAction
{
    public function execute(Complaint $complaint, ?string $reason = null): Complaint
    {
        if ($complaint->status === ComplaintStatus::RESOLVED->value) {
            throw new \LogicException('Resolved complaints cannot be escalated.');
        }

        $complaint->status = ComplaintStatus::PENDING->value;
        $complaint->priority = ComplaintSeverity::HIGH->value;
        $complaint->save();

        if ($reason !== null && trim($reason) !== '') {
            ComplaintReply::query()->create([
                'complaint_id' => $complaint->id,
                'user_id' => auth()->id(),
                'company_id' => $complaint->company_id,
                'message' => trim($reason),
            ]);
        }

        ComplaintEscalated::dispatch($complaint, $reason);

        return $complaint;
    }
}
