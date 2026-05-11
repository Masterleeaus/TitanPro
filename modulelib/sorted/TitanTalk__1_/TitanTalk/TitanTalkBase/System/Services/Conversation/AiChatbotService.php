<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\Conversation;

use App\Extensions\MarketingBot\System\AI\Intent\IntentEngine;
use App\Extensions\MarketingBot\System\Commands\CommandInterpreter;
use App\Extensions\MarketingBot\System\Conversation\ConversationStateMachine;
use App\Extensions\MarketingBot\System\Conversation\GoalEngine;
use App\Extensions\MarketingBot\System\Memory\ConversationContextBuilder;
use App\Extensions\MarketingBot\System\Enums\ConversationIntent;
use App\Extensions\MarketingBot\System\Models\MarketingCampaign;
use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Services\Generator\GeneratorService;
use App\Extensions\MarketingBot\System\Services\TitanTalk\Tools\ToolCallEngine;
use App\Extensions\MarketingBot\System\Signals\TitanTalkSignalBridge;
use App\Extensions\MarketingBot\System\Safety\CommandSafetyService;
use App\Extensions\MarketingBot\System\Sequences\FollowupSequenceService;
use App\Extensions\MarketingBot\System\Memory\PersistentMemoryService;
use App\Extensions\MarketingBot\System\Workflows\WorkflowOrchestrator;

class AiChatbotService
{
    public function __construct(
        protected IntentClassifierService $intentClassifier,
        protected ConversationStateService $conversationStateService,
        protected ToolIntentRegistryService $toolIntentRegistryService,
        protected GeneratorService $generatorService,
        protected IntentEngine $intentEngine,
        protected GoalEngine $goalEngine,
        protected ConversationStateMachine $conversationStateMachine,
        protected ToolCallEngine $toolCallEngine,
        protected CommandInterpreter $commandInterpreter,
        protected TitanTalkSignalBridge $signalBridge,
        protected ConversationContextBuilder $conversationContextBuilder,
        protected CommandSafetyService $commandSafetyService,
        protected FollowupSequenceService $followupSequenceService,
        protected PersistentMemoryService $persistentMemoryService,
        protected WorkflowOrchestrator $workflowOrchestrator,
    ) {}

    public function reply(MarketingConversation $conversation, string $prompt, ?MarketingCampaign $campaign = null): string
    {
        return $this->replyWithContext($conversation, $prompt, $campaign)['reply'];
    }

    /**
     * @return array<string,mixed>
     */
    public function replyWithContext(MarketingConversation $conversation, string $prompt, ?MarketingCampaign $campaign = null): array
    {
        $classification = $this->intentEngine->detect($prompt, [
            'channel' => $conversation->type,
            'conversation_id' => $conversation->getKey(),
        ]);

        $toolPlans = config('titantalk.tools_enabled', true)
            ? $this->toolCallEngine->planForConversation($conversation, $classification)
            : [];

        $classification['goal'] = $this->goalEngine->determine($classification['intent']);
        $classification['tool_plans'] = $toolPlans;
        $classification['command'] = $this->commandInterpreter->interpret($prompt, $classification);
        $classification['safety'] = $this->commandSafetyService->assess($classification['command'], $classification);
        $classification['sequence'] = $this->followupSequenceService->suggest($conversation, $classification);
        $classification['next_state'] = $this->conversationStateMachine
            ->nextState(
                $conversation->state,
                $classification['intent'],
                $toolPlans !== [],
                (bool) ($classification['should_handoff'] ?? false)
            )->value;

        $conversation = $this->conversationStateService->syncIntentAndState($conversation, $classification);

        $contextBundle = $this->conversationContextBuilder->build($conversation);
        $workflow = $this->workflowOrchestrator->build($conversation, $classification);
        $systemContext = $this->buildSystemContext($conversation, $classification, $contextBundle);

        if ($campaign === null) {
            $campaign = $this->makeVirtualCampaign($conversation, $classification);
        }

        $reply = $this->generatorService
            ->setMarketingCampaign($campaign)
            ->setConversation($conversation)
            ->setPrompt($prompt)
            ->setAdditionalSystemContext($systemContext)
            ->generate();

        $signal = $this->signalBridge->emitFromCommand($conversation, $classification['command'], (string) $conversation->type);
        $conversation->update(['customer_payload' => array_merge((array) ($conversation->customer_payload ?? []), ['last_signal' => $signal, 'active_processes' => [$workflow]])]);
        $this->persistentMemoryService->remember($conversation, $classification, $contextBundle, $workflow);

        return [
            'reply' => $reply,
            'classification' => $classification,
            'tool_plans' => $toolPlans,
            'command' => $classification['command'],
            'safety' => $classification['safety'],
            'sequence' => $classification['sequence'],
            'signal' => $signal,
            'workflow' => $workflow,
            'context' => $contextBundle,
            'conversation' => $conversation->fresh(),
        ];
    }

    protected function buildSystemContext(MarketingConversation $conversation, array $classification, array $contextBundle = []): string
    {
        /** @var ConversationIntent $intent */
        $intent = $classification['intent'];
        $tools = implode(', ', $this->toolIntentRegistryService->toolsForIntent($intent->value));
        $goal = (string) ($classification['goal'] ?? $conversation->goal ?? 'answer_question');
        $nextState = (string) ($classification['next_state'] ?? $conversation->state ?? 'new');
        $toolLines = $this->formatToolPlans((array) ($classification['tool_plans'] ?? []));

        return implode("\n", [
            'You are TitanTalk, a real service-business conversation assistant embedded inside the operating system.',
            'Primary intent: ' . $intent->value . ' (' . $intent->label() . ').',
            'Current conversation state: ' . ($conversation->state ?? 'new') . '.',
            'Suggested next state: ' . $nextState . '.',
            'Current business goal: ' . $goal . '.',
            'Current role pack: ' . ($conversation->role_pack ?? 'titantalk.general') . '.',
            'Respond as an operations-aware assistant, not as a generic marketing bot.',
            'When a real system action is needed, suggest the next best tool instead of pretending it already happened.',
            'Preferred tools for this intent: ' . $tools . '.',
            'Planned Predix tool requests: ' . $toolLines . '.',
            'Safety assessment: ' . json_encode($classification['safety'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '.',
            'Suggested follow-up sequence: ' . json_encode($classification['sequence'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '.',
            'If confidence is low, ask one clarifying question instead of making up facts.',
            'If the customer explicitly asks for a person, acknowledge and mark this thread for human handoff.',
            'If state is escalated or role pack is titantalk.operator, explain that a human teammate will review the thread.',
            $this->conversationContextBuilder->asSystemContext($conversation),
            'Context summary object: ' . json_encode($contextBundle, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }

    /**
     * @param array<int,array<string,mixed>> $toolPlans
     */
    protected function formatToolPlans(array $toolPlans): string
    {
        if ($toolPlans === []) {
            return 'none';
        }

        $parts = [];
        foreach ($toolPlans as $plan) {
            $parts[] = sprintf('%s(%s)', (string) ($plan['tool'] ?? 'unknown'), $plan['approval_required'] ? 'approval' : 'auto');
        }

        return implode(', ', $parts);
    }

    protected function makeVirtualCampaign(MarketingConversation $conversation, array $classification): MarketingCampaign
    {
        /** @var ConversationIntent $intent */
        $intent = $classification['intent'];

        $campaign = new MarketingCampaign();
        $campaign->forceFill([
            'user_id' => $conversation->user_id,
            'name' => 'TitanTalk Assistant',
            'type' => $conversation->type === 'telegram' ? 'telegram' : 'whatsapp',
            'instruction' => match ($intent) {
                ConversationIntent::BOOKING => 'Help qualify the booking, gather the missing details, and guide the customer toward an actual booking.',
                ConversationIntent::QUOTE => 'Help gather quoting details, clarify scope, and prepare the customer for a formal quote.',
                ConversationIntent::SUPPORT => 'Be calm, useful, and solution-oriented. Summarize the issue and collect the details needed for resolution.',
                ConversationIntent::COMPLAINT => 'Use service recovery language: acknowledge the issue, apologize appropriately, and move toward resolution or escalation.',
                ConversationIntent::INVOICE => 'Help explain invoice or payment status clearly, and offer the next step without inventing account facts.',
                ConversationIntent::RESCHEDULE => 'Confirm the customer wants a reschedule and collect the minimum details needed to move the booking.',
                ConversationIntent::CANCEL => 'Confirm cancellation intent, explain that the request will be processed, and avoid promising policy outcomes you cannot verify.',
                ConversationIntent::HUMAN_HANDOFF => 'Acknowledge the handoff request, gather minimal details, and reassure the customer that a person will review the thread.',
                default => 'Answer the customer clearly and concisely, using the knowledge base when relevant.',
            },
        ]);

        $campaign->exists = false;

        return $campaign;
    }
}
