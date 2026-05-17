<?php

namespace Modules\TitanEchoAssist\Events;

class MessageBilled
{
    public function __construct(
        public readonly int    $companyId,
        public readonly string $channel,
        public readonly string $sessionId,
        public readonly int    $chatbotId,
        public readonly string $billingPeriod,
        public readonly int    $tokenCount   = 0,
        public readonly float  $cost         = 0.0,
        public readonly string $billedAt     = '',
    ) {}

    public static function fromUsage(
        int    $companyId,
        string $channel,
        string $sessionId,
        int    $chatbotId,
        int    $tokenCount = 0,
        float  $cost       = 0.0,
    ): self {
        return new self(
            companyId:     $companyId,
            channel:       $channel,
            sessionId:     $sessionId,
            chatbotId:     $chatbotId,
            billingPeriod: date('Y-m'),
            tokenCount:    $tokenCount,
            cost:          $cost,
            billedAt:      date('c'),
        );
    }

    public function toArray(): array
    {
        return [
            'company_id'     => $this->companyId,
            'channel'        => $this->channel,
            'session_id'     => $this->sessionId,
            'chatbot_id'     => $this->chatbotId,
            'billing_period' => $this->billingPeriod,
            'token_count'    => $this->tokenCount,
            'cost'           => $this->cost,
            'billed_at'      => $this->billedAt,
        ];
    }
}
