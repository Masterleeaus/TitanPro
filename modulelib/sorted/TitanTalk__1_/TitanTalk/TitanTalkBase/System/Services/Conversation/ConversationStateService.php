<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\Conversation;

use App\Extensions\MarketingBot\System\Conversation\ConversationStateMachine;
use App\Extensions\MarketingBot\System\Conversation\GoalEngine;
use App\Extensions\MarketingBot\System\Enums\ConversationIntent;
use App\Extensions\MarketingBot\System\Services\TitanTalk\HandoffService;
use App\Extensions\MarketingBot\System\Services\TitanTalk\RolePackResolverService;
use App\Extensions\MarketingBot\System\Models\MarketingConversation;

class ConversationStateService
{
    public function __construct(
        protected RolePackResolverService $rolePackResolverService,
        protected HandoffService $handoffService,
        protected GoalEngine $goalEngine,
        protected ConversationStateMachine $conversationStateMachine,
    ) {}

    public function syncIntentAndState(MarketingConversation $conversation, array $classification): MarketingConversation
    {
        /** @var ConversationIntent $intent */
        $intent = $classification['intent'];
        $toolPlans = (array) ($classification['tool_plans'] ?? []);
        $goal = (string) ($classification['goal'] ?? $this->goalEngine->determine($intent));

        $state = $this->conversationStateMachine->nextState(
            $conversation->state,
            $intent,
            $toolPlans !== [],
            (bool) ($classification['should_handoff'] ?? false)
        );

        $conversation->fill([
            'intent' => $intent->value,
            'intent_confidence' => $classification['confidence'] ?? 0,
            'state' => $classification['next_state'] ?? $state->value,
            'goal' => $goal,
            'role_pack' => $this->rolePackResolverService->resolve($intent),
            'last_classified_at' => now(),
            'last_activity_at' => now(),
            'handoff_requested_at' => (($classification['should_handoff'] ?? false) === true || $intent === ConversationIntent::HUMAN_HANDOFF) ? now() : $conversation->handoff_requested_at,
            'customer_payload' => array_merge((array) ($conversation->customer_payload ?? []), [
                'last_entities' => (array) ($classification['entities'] ?? []),
                'last_tool_plans' => $toolPlans,
            ]),
        ])->save();

        if (($classification['should_handoff'] ?? false) === true || $intent === ConversationIntent::HUMAN_HANDOFF || $intent === ConversationIntent::COMPLAINT) {
            $this->handoffService->queue($conversation, (string) ($classification['handoff_reason'] ?? 'Conversation requires human takeover.'), [
                'matched_rule' => $classification['matched_rule'] ?? null,
                'confidence' => $classification['confidence'] ?? null,
                'tool_plans' => $toolPlans,
            ]);
        }

        return $conversation->refresh();
    }
}
