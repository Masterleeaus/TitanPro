<?php

namespace Modules\ZeroFussPortal\Actions;

use Modules\ZeroFussPortal\Events\FeedbackSubmitted;
use Modules\ZeroFussPortal\Models\PortalFeedback;

class SubmitFeedbackAction
{
    public function execute(int $companyId, int $customerId, string $message, ?int $rating = null, array $metadata = []): PortalFeedback
    {
        $feedback = PortalFeedback::query()->create([
            'company_id' => $companyId,
            'customer_id' => $customerId,
            'message' => trim($message),
            'rating' => $rating,
            'metadata' => $metadata,
        ]);

        FeedbackSubmitted::dispatch($feedback);

        return $feedback;
    }
}
