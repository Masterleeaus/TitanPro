<?php

namespace Modules\Complaint\Actions;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Complaint\Entities\Complaint;
use Modules\Complaint\Entities\ComplaintReply;
use Modules\Complaint\Entities\ComplaintTagList;
use Modules\Complaint\Events\ComplaintReceived;
use Modules\Complaint\Services\ComplaintAnalysisService;
use Modules\Complaint\Support\Enums\ComplaintSeverity;
use Modules\Complaint\Support\Enums\ComplaintStatus;
use Modules\Engineerings\Entities\WorkRequest;

class CreateComplaintAction
{
    private const WORK_REQUEST_NUMBER_WIDTH = 4;
    public const DEFAULT_NO_HP = 'N/A';

    public function __construct(private readonly ComplaintAnalysisService $analysisService)
    {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Complaint
    {
        $subject = trim((string) ($data['subject'] ?? ''));

        if ($subject === '') {
            throw new \InvalidArgumentException('Complaint subject is required.');
        }

        return DB::transaction(function () use ($data, $subject): Complaint {
            $description = (string) ($data['description'] ?? '');
            $analysis = $this->analysisService->analyse($subject, $description);
            $severity = ComplaintSeverity::fromAnalysis($analysis['severity']);

            $complaint = new Complaint();
            $complaint->subject = $subject;
            $complaint->status = (string) ($data['status'] ?? ComplaintStatus::OPEN->value);
            $complaint->priority = (string) ($data['priority'] ?? $severity->value);
            $complaint->no_hp = (string) ($data['no_hp'] ?? self::DEFAULT_NO_HP);
            $complaint->house_id = $data['house_id'] ?? null;
            $complaint->user_id = $data['user_id'] ?? null;
            $complaint->agent_id = $data['agent_id'] ?? null;
            $complaint->type_id = $data['type_id'] ?? null;
            $complaint->channel_id = $data['channel_id'] ?? null;
            $complaint->company_id = $data['company_id'] ?? null;
            $complaint->added_by = $data['added_by'] ?? null;
            $complaint->last_update_by = $data['last_update_by'] ?? null;
            $complaint->save();

            $firstReplyId = null;
            if ($description !== '') {
                $reply = new ComplaintReply();
                $reply->message = $this->sanitizeDescription($description);
                $reply->complaint_id = $complaint->id;
                $reply->user_id = $data['reply_user_id'] ?? $complaint->user_id;
                $reply->company_id = $complaint->company_id;
                $reply->save();
                $firstReplyId = $reply->id;
            }

            $tags = $this->normaliseTags($data['tags'] ?? []);
            $tagIds = [];
            foreach ($tags as $tagName) {
                $tag = ComplaintTagList::firstOrCreate(['tag_name' => $tagName]);
                $tagIds[] = $tag->id;
            }
            if (! empty($tagIds)) {
                $complaint->complaintTags()->syncWithoutDetaching($tagIds);
            }

            if (($data['create_work_request'] ?? false) === true) {
                $this->createWorkRequest($complaint);
            }

            ComplaintReceived::dispatch($complaint);
            $complaint->setAttribute('first_reply_id', $firstReplyId);

            return $complaint;
        });
    }

    /**
     * @param mixed $rawTags
     * @return Collection<int, string>
     */
    private function normaliseTags(mixed $rawTags): Collection
    {
        if (is_string($rawTags)) {
            $decoded = json_decode($rawTags, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $rawTags = $decoded;
            }
        }

        return collect(is_array($rawTags) ? $rawTags : [])
            ->map(function ($tag): string {
                if (is_array($tag)) {
                    return (string) ($tag['value'] ?? '');
                }

                return (string) $tag;
            })
            ->map(fn (string $tag): string => trim($tag))
            ->filter(fn (string $tag): bool => $tag !== '')
            ->values();
    }

    private function createWorkRequest(Complaint $complaint): void
    {
        $number = WorkRequest::lastInvoiceNumber() + 1;

        $wr = new WorkRequest();
        $wr->complaint_id = $complaint->id;
        $wr->wr_no = 'WR-' . Carbon::now()->format('ym') . '-' . str_pad((string) $number, self::WORK_REQUEST_NUMBER_WIDTH, '0', STR_PAD_LEFT);
        $wr->check_time = now();
        $wr->problem = $complaint->subject;
        $wr->house_id = $complaint->house_id;
        $wr->assign_to = $complaint->agent_id;
        $wr->created_by = $complaint->added_by ?? $complaint->user_id;
        $wr->save();
    }

    private function sanitizeDescription(string $description): string
    {
        return function_exists('trim_editor') ? trim_editor($description) : trim($description);
    }
}
