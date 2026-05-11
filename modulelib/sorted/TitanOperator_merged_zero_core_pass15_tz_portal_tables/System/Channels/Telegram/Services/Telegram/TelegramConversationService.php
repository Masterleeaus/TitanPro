<?php

namespace App\Extensions\TitanOperator\System\Channels\Telegram\Services\Telegram;

use App\Extensions\TitanOperator\System\Enums\InteractionType;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorHistory;
use App\Extensions\TitanOperator\System\Services\GeneratorService;
use App\Extensions\TitanOperatorAgent\System\Services\TitanOperatorForPanelEventAbly;
use App\Helpers\Classes\Helper;
use App\Helpers\Classes\MarketplaceHelper;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Fluent;

class TelegramConversationService
{
    protected string $humanAgentCommand = 'humanagent';

    protected bool $existMessage = false;

    protected int $channelId;

    protected ?TitanOperator $titan_operator = null;

    protected ?TitanOperatorConversation $conversation = null;

    protected ?TitanOperatorHistory $history = null;

    protected ?Fluent $payload = null;

    protected ?string $ipAddress = null;

    public function handleTelegram(): void
    {
        $telegram = app(TelegramService::class)->setChannel(TitanOperatorChannel::find($this->channelId));
        $conversation = $this->conversation;
        $titan_operator = $conversation->titan_operator;
        $customerChannelId = $this->getCustomerChannelId();

        if ($conversation->connect_agent_at) {
            if ($conversation->last_activity_at->diffInMinutes() > 10) {
                $this->closeInactiveConversation($conversation, $telegram, $customerChannelId);

                return;
            }

            return;
        }

        $conversation->update(['last_activity_at' => now()]);

        $messageBody = data_get($this->payload, 'message.text');

        if (is_string($messageBody)) {
            $this->processTextMessage($messageBody, $conversation, $titan_operator, $telegram, $customerChannelId);
        } else {
            $this->sendUnsupportedMessageType($conversation, $titan_operator, $telegram, $customerChannelId);
        }
    }

    protected function processTextMessage(string $messageBody, TitanOperatorConversation $conversation, TitanOperator $titan_operator, TelegramService $telegram, $customerChannelId): void
    {
        if (! $this->existMessage) {
            $this->sendWelcomeMessage($titan_operator, $conversation, $telegram, $customerChannelId);

            return;
        }

        if ($this->isHumanAgentCommand($titan_operator, $messageBody)) {
            $this->connectToHumanAgent($titan_operator, $conversation, $telegram, $customerChannelId);

            return;
        }

        $response = $this->generateResponse($messageBody) ?? trans("Sorry, I can't answer right now.");

        if (! $conversation->connect_agent_at && $titan_operator->interaction_type === InteractionType::SMART_SWITCH && MarketplaceHelper::isRegistered('titan_operator_agent')) {
            $response .= "\n\n\nTo speak with a live support agent, please enter the #{$this->humanAgentCommand} command.";
        }

        $telegram->sendText($response, $customerChannelId);

        $this->insertMessage($conversation, $response, 'assistant', $titan_operator->ai_model);
    }

    protected function closeInactiveConversation(TitanOperatorConversation $conversation, TelegramService $telegram, $customerChannelId): void
    {
        $conversation->update(['connect_agent_at' => null]);
        $message = trans('The conversation has been closed due to inactivity.');
        $this->insertMessage($conversation, $message, 'assistant', $conversation->titan_operator->ai_model);
        $telegram->sendText($message, $customerChannelId);
    }

    protected function sendWelcomeMessage(TitanOperator $titan_operator, TitanOperatorConversation $conversation, TelegramService $telegram, $customerChannelId): void
    {
        if ($welcomeMessage = $titan_operator->welcome_message) {
            $this->insertMessage($conversation, $welcomeMessage, 'assistant', $titan_operator->ai_model);
            $telegram->sendText($welcomeMessage, $customerChannelId);
        }
    }

    protected function sendUnsupportedMessageType(TitanOperatorConversation $conversation, TitanOperator $titan_operator, TelegramService $telegram, $customerChannelId): void
    {
        $message = trans('The titan_operator does not support the type of message you are sending.');
        $this->insertMessage($conversation, $message, 'assistant', $titan_operator->ai_model);
        $telegram->sendText($message, $customerChannelId);
    }

    protected function connectToHumanAgent(TitanOperator $titan_operator, TitanOperatorConversation $conversation, TelegramService $telegram, $customerChannelId): void
    {
        $conversation->update(['connect_agent_at' => now()]);

        if ($connectMessage = $titan_operator->connect_message) {
            $operatorHistory = $this->insertMessage($conversation, $connectMessage, 'assistant', $titan_operator->ai_model, true);
            $telegram->sendText($connectMessage, $customerChannelId);

            $this->dispatchAgentEvent($titan_operator, $conversation, $operatorHistory);
        }
    }

    protected function dispatchAgentEvent(TitanOperator $titan_operator, TitanOperatorConversation $conversation, ?TitanOperatorHistory $operatorHistory): void
    {
        if (MarketplaceHelper::isRegistered('titan_operator_agent')) {
            try {
                TitanOperatorForPanelEventAbly::dispatch($titan_operator, $conversation->load('lastMessage'), $operatorHistory);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }

    protected function generateResponse(string $prompt): ?string
    {
        return app(GeneratorService::class)
            ->setTitanOperator($this->conversation->titan_operator)
            ->setConversation($this->conversation)
            ->setPrompt($prompt)
            ->generate();
    }

    protected function isHumanAgentCommand(TitanOperator $titan_operator, string $message): bool
    {
        return str_contains($message, $this->humanAgentCommand) && $titan_operator->getAttribute('interaction_type') === InteractionType::SMART_SWITCH;
    }

    public function insertMessage(TitanOperatorConversation $conversation, string $message, string $role, string $model, bool $forcePanelEvent = false)
    {
        $titan_operator = $conversation->getAttribute('titan_operator');

        $operatorHistory = TitanOperatorHistory::create([
            'operator_id'      => $conversation->getAttribute('operator_id'),
            'conversation_id' => $conversation->getAttribute('id'),
            'type'            => $conversation->getAttribute('operator_channel') ?? 'telegram',
            'message_id'      => (string) (data_get($this->payload, 'message.message_id') ?? data_get($this->payload, 'update_id') ?? ''),
            'role'            => $role,
            'model'           => $model,
            'message'         => $message,
            'message_type'    => 'text',
            'content_type'    => 'text',
            'created_at'      => now(),
            'read_at'         => $conversation->getAttribute('connect_agent_at') ? null : now(),
        ]);

        $this->history = $operatorHistory;

        $sendEvent = $conversation->getAttribute('connect_agent_at') && $titan_operator->getAttribute('interaction_type') !== InteractionType::AUTOMATIC_RESPONSE && $role === 'user';

        if ($sendEvent || $forcePanelEvent) {
            $conversation->touch();
            if (MarketplaceHelper::isRegistered('titan_operator_agent')) {
                try {
                    TitanOperatorForPanelEventAbly::dispatch(
                        $titan_operator,
                        $conversation->load('lastMessage'),
                        $operatorHistory
                    );
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }

        return $operatorHistory;
    }

    public function storeConversation(): Builder|Model|TitanOperatorConversation|null
    {
        $this->titan_operator = TitanOperator::find($this->operatorId);

        if (! $this->titan_operator) {
            return null;
        }

        $customer_channel_id = $this->getCustomerChannelId();

        $this->conversation = TitanOperatorConversation::firstOrCreate([
            'operator_id'          => $this->operatorId,
            'operator_channel'     => 'telegram',
            'operator_channel_id'  => $this->channelId,
            'customer_channel_id' => $customer_channel_id,
        ], [
            'session_id'        => md5(uniqid(mt_rand(), true)),
            'conversation_name' => data_get($this->payload, 'message.chat.first_name') . ' ' . data_get($this->payload, 'message.chat.last_name'),
            'ip_address'        => $this->ipAddress,
            'connect_agent_at'  => $this->titan_operator?->getAttribute('interaction_type') === InteractionType::HUMAN_SUPPORT ? now() : null,
            'customer_payload'  => [
                'From'       => $customer_channel_id,
            ],
            'last_activity_at' => now(),
        ]);

        $this->existMessage = TitanOperatorHistory::query()
            ->where('conversation_id', $this->conversation->getKey())
            ->exists();

        $this->conversation->setRelation('titan_operator', $this->titan_operator);

        return $this->conversation;
    }

    public function getCustomerChannelId()
    {
        return data_get($this->payload, 'message.chat.id');
    }

    public function getChannelId(): int
    {
        return $this->channelId;
    }

    public function setChannelId(int $channelId): self
    {
        $this->channelId = $channelId;

        return $this;
    }

    public function setTitanOperator(Model|Builder|TitanOperator|null $titan_operator): self
    {
        $this->titan_operator = $titan_operator;

        return $this;
    }

    public function getTitanOperator(): Model|Builder|TitanOperator|null
    {
        return $this->titan_operator;
    }

    public function setConversation(Model|TitanOperatorConversation|Builder|null $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function setHistory(Model|TitanOperatorHistory|Builder|null $history): self
    {
        $this->history = $history;

        return $this;
    }

    public function setTitanOperatorId(int $operatorId): self
    {
        $this->operatorId = $operatorId;

        return $this;
    }

    public function getTitanOperatorId(): int
    {
        return $this->operatorId;
    }

    public function setIpAddress(?int $ipAddress = null): self
    {
        if ($ipAddress) {
            $this->ipAddress = $ipAddress;
        } else {
            $this->ipAddress = request()?->header('cf-connecting-ip') ?? request()?->ip();
        }

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getPayload(): null|array|Fluent
    {
        return $this->payload;
    }

    public function setPayload(?array $payload = null): self
    {
        $this->payload = new Fluent($payload ?: []);

        return $this;
    }
}
