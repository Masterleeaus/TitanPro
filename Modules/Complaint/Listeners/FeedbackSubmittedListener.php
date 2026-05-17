<?php

namespace Modules\Complaint\Listeners;

use Illuminate\Support\Arr;
use Modules\Complaint\Actions\CreateComplaintAction;
use Modules\ZeroFussPortal\Events\FeedbackSubmitted;

class FeedbackSubmittedListener
{
    private const DEFAULT_PHONE_PLACEHOLDER = 'N/A';

    public function __construct(private readonly CreateComplaintAction $createComplaintAction)
    {
    }

    public function handle($event): void
    {
        [$rating, $companyId, $customerId, $message] = $this->extractFeedbackData($event);

        if ($rating === null || $rating >= 3) {
            return;
        }

        $this->createComplaintAction->execute([
            'company_id' => $companyId,
            'user_id' => $customerId,
            'added_by' => $customerId,
            'last_update_by' => $customerId,
            'subject' => 'Auto complaint from low feedback rating',
            'description' => $message !== '' ? $message : 'Auto-generated from ZeroFussPortal.FeedbackSubmitted',
            'no_hp' => self::DEFAULT_PHONE_PLACEHOLDER,
            'priority' => 'high',
            'tags' => ['feedback', 'auto-generated'],
        ]);
    }

    /**
     * @return array{0: int|null, 1: int|null, 2: int|null, 3: string}
     */
    private function extractFeedbackData(mixed $event): array
    {
        if ($event instanceof FeedbackSubmitted) {
            return [
                $event->feedback->rating,
                $event->feedback->company_id,
                $event->feedback->customer_id,
                (string) $event->feedback->message,
            ];
        }

        $payload = is_array($event) ? $event : (array) $event;

        return [
            Arr::get($payload, 'rating'),
            Arr::get($payload, 'company_id'),
            Arr::get($payload, 'customer_id'),
            (string) Arr::get($payload, 'message', ''),
        ];
    }
}
