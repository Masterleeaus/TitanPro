<?php

namespace Modules\TitanLeads\Services\Outbox;

use Modules\TitanLeads\Models\OutboxApproval;
use Modules\TitanLeads\Models\OutboxDraft;
use Modules\TitanLeads\Services\Email\EmailSenderService;
use Modules\TitanLeads\Services\Sms\SmsSenderService;
use Modules\TitanLeads\Services\Voice\VoiceSenderService;
use Illuminate\Support\Str;
use Throwable;

class OutboxService
{
    public function __construct(
        public SmsSenderService $sms,
        public VoiceSenderService $voice,
        public EmailSenderService $email,
    ) {}

    public function createDraft(array $data): OutboxDraft
    {
        return OutboxDraft::query()->create($data);
    }

    public function requestTitanZeroApproval(OutboxDraft $draft): OutboxApproval
    {
        $token = Str::random(48);
        $draft->update([
            'status' => 'pending_approval',
            'requires_approval' => true,
        ]);

        return OutboxApproval::query()->create([
            'draft_id' => $draft->getKey(),
            'approver' => 'titan_zero',
            'approval_status' => 'pending',
            'approval_token' => $token,
        ]);
    }

    public function markApproved(OutboxApproval $approval, array $payload = []): void
    {
        $approval->update([
            'approval_status' => 'approved',
            'approval_payload' => $payload,
            'decided_at' => now(),
        ]);

        $draft = OutboxDraft::query()->find($approval->draft_id);
        if ($draft) {
            $draft->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        }
    }

    public function sendNow(OutboxDraft $draft, bool $isManualUserSend = false): array
    {
        // Manual sends always allowed.
        // AI sends only allowed when approved.
        if (!$isManualUserSend) {
            if ($draft->requires_approval && $draft->status !== 'approved') {
                return ['status' => false, 'message' => 'Approval required'];
            }
        }

        try {
            $res = match ($draft->channel) {
                'sms' => $this->sendSms($draft),
                'voice' => $this->sendVoice($draft),
                'email' => $this->sendEmail($draft),
                default => ['status' => false, 'message' => 'Unsupported channel'],
            };

            if (($res['status'] ?? false) === true) {
                $draft->update(['status' => 'sent', 'sent_at' => now(), 'last_error' => null]);
            } else {
                $draft->update(['status' => 'failed', 'last_error' => $res['message'] ?? 'failed']);
            }

            return $res;
        } catch (Throwable $e) {
            $draft->update(['status' => 'failed', 'last_error' => $e->getMessage()]);
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    private function sendSms(OutboxDraft $draft): array
    {
        $this->sms->setSmsChannel((int)$draft->user_id);
        return $this->sms->sendText((string)$draft->to, (string)$draft->body);
    }

    private function sendVoice(OutboxDraft $draft): array
    {
        $this->voice->setVoiceChannel((int)$draft->user_id);
        return $this->voice->callAndSpeak((string)$draft->to, (string)$draft->body);
    }

    private function sendEmail(OutboxDraft $draft): array
    {
        $this->email->setEmailChannel((int)$draft->user_id);
        return $this->email->send((string)$draft->to, (string)($draft->subject ?? ''), (string)$draft->body);
    }
}
