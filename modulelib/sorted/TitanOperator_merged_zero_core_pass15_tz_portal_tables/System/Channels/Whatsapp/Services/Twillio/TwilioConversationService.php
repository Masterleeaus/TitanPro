<?php

namespace App\Extensions\TitanOperator\System\Channels\Whatsapp\Services\Twillio;

use App\Extensions\TitanOperator\System\Enums\InteractionType;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorHistory;
use App\Extensions\TitanOperator\System\Services\GeneratorService;
use App\Extensions\TitanOperator\System\Agent\Services\TitanOperatorForPanelEventAbly;
use App\Helpers\Classes\Helper;
use App\Helpers\Classes\MarketplaceHelper;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class TwilioConversationService
{
    protected ?TitanOperatorConversation $conversation = null;

    protected ?TitanOperatorHistory $history = null;

    protected ?TitanOperator $titan_operator = null;

    protected string $humanAgentCommand = 'humanagent';

    protected int $operatorId;

    protected int $channelId;

    protected ?string $ipAddress = null;

    protected ?array $payload = null;

    protected bool $existMessage = false;

    public function handleWhatsapp(): void
    {
        $twilio = app(TwilioWhatsappService::class)
            ->setTitanOperatorChannel(TitanOperatorChannel::find($this->channelId));

        $waId = '+' . data_get($this->payload, 'WaId');
        $messageType = data_get($this->payload, 'MessageType');
        $messageBody = data_get($this->payload, 'Body');

        $conversation = $this->conversation;
        $titan_operator = $conversation->titan_operator;

        if ($conversation->connect_agent_at) {
            if ($conversation->last_activity_at->diffInMinutes() > 10) {
                $this->closeInactiveConversation($conversation, $twilio, $waId);

                return;
            }

            return;
        }

        $conversation->update(['last_activity_at' => now()]);

        if ($messageType === 'text' && is_string($messageBody)) {
            $this->processTextMessage($messageBody, $conversation, $titan_operator, $twilio, $waId);
        } else {
            $this->sendUnsupportedMessageType($conversation, $titan_operator, $twilio, $waId);
        }
    }

    protected function closeInactiveConversation(TitanOperatorConversation $conversation, TwilioWhatsappService $twilio, string $waId): void
    {
        $conversation->update(['connect_agent_at' => null]);
        $message = trans('The conversation has been closed due to inactivity.');
        $this->insertMessage($conversation, $message, 'assistant', $conversation->titan_operator->ai_model);
        $twilio->sendText($message, $waId);
    }

    protected function processTextMessage(string $messageBody, TitanOperatorConversation $conversation, TitanOperator $titan_operator, TwilioWhatsappService $twilio, string $waId): void
    {
        if ($this->isHumanAgentCommand($titan_operator, $messageBody)) {
            $this->connectToHumanAgent($titan_operator, $conversation, $twilio, $waId);

            return;
        }

        $response = $this->generateResponse($messageBody) ?? trans("Sorry, I can't answer right now.");

        if (! $conversation->connect_agent_at && $titan_operator->interaction_type === InteractionType::SMART_SWITCH && MarketplaceHelper::isRegistered('titan_operator_agent')) {
            $response .= "\n\n\nTo speak with a live support agent, please enter the #{$this->humanAgentCommand} command.";
        }

        $twilio->sendText($response, $waId);
        $this->insertMessage($conversation, $response, 'assistant', $titan_operator->ai_model);
    }

    protected function sendUnsupportedMessageType(TitanOperatorConversation $conversation, TitanOperator $titan_operator, TwilioWhatsappService $twilio, string $waId): void
    {
        $message = trans('The titan_operator does not support the type of message you are sending.');
        $this->insertMessage($conversation, $message, 'assistant', $titan_operator->ai_model);
        $twilio->sendText($message, $waId);
    }

    protected function connectToHumanAgent(TitanOperator $titan_operator, TitanOperatorConversation $conversation, TwilioWhatsappService $twilio, string $waId): void
    {
        $conversation->update(['connect_agent_at' => now()]);

        if ($connectMessage = $titan_operator->connect_message) {
            $operatorHistory = $this->insertMessage($conversation, $connectMessage, 'assistant', $titan_operator->ai_model, true);
            $twilio->sendText($connectMessage, $waId);
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

    protected function isHumanAgentCommand(TitanOperator $titan_operator, string $message): bool
    {
        return str_contains($message, $this->humanAgentCommand) && $titan_operator->interaction_type === InteractionType::SMART_SWITCH;
    }

    protected function generateResponse(string $prompt): ?string
    {
        return app(GeneratorService::class)
            ->setTitanOperator($this->conversation->titan_operator)
            ->setConversation($this->conversation)
            ->setPrompt($prompt)
            ->generate();
    }

    public function insertMessage(TitanOperatorConversation $conversation, string $message, string $role, string $model, bool $forcePanelEvent = false)
    {
        $titan_operator = $conversation->getAttribute('titan_operator');

        $operatorHistory = TitanOperatorHistory::query()->create([
            'operator_id'      => $conversation->getAttribute('operator_id'),
            'conversation_id' => $conversation->getAttribute('id'),
            'type'            => $conversation->getAttribute('operator_channel') ?? 'whatsapp',
            'message_id'      => data_get($this->payload, 'SmsSid'),
            'role'            => $role,
            'model'           => Helper::setting('openai_default_model'),
            'message'         => $message,
            'message_type'    => data_get($this->payload, 'MessageType') ?? 'text',
            'content_type'    => data_get($this->payload, 'MediaContentType0') ?? 'text',
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

    public function storeHistory(Builder|Model|null $conversation = null): void
    {
        $conversation ??= $this->conversation;

        $this->existMessage = TitanOperatorHistory::query()
            ->where('conversation_id', $conversation->getKey())
            ->exists();

        $this->history = TitanOperatorHistory::create([
            'operator_id'      => $conversation->getAttribute('operator_id'),
            'conversation_id' => $conversation->getKey(),
            'type'            => $conversation->getAttribute('operator_channel') ?? 'whatsapp',
            'message_id'      => data_get($this->payload, 'SmsSid'),
            'role'            => 'user',
            'model'           => Helper::setting('openai_default_model'),
            'message'         => data_get($this->payload, 'Body', ''),
            'message_type'    => data_get($this->payload, 'MessageType') ?? 'text',
            'content_type'    => data_get($this->payload, 'MediaContentType0') ?? 'text',
            'read_at'         => $conversation->getAttribute('connect_agent_at') ? null : now(),
            'created_at'      => now(),
        ]);
    }

    public function storeConversation(): Builder|Model|TitanOperatorConversation
    {
        $this->titan_operator = TitanOperator::find($this->operatorId);

        $this->conversation = TitanOperatorConversation::firstOrCreate([
            'operator_id'          => $this->operatorId,
            'operator_channel'     => 'whatsapp',
            'operator_channel_id'  => $this->channelId,
            'customer_channel_id' => $this->getCustomerChannelId(),
        ], [
            'session_id'        => md5(uniqid(mt_rand(), true)),
            'conversation_name' => data_get($this->payload, 'WaId'),
            'ip_address'        => $this->ipAddress,
            'connect_agent_at'  => $this->titan_operator->getAttribute('interaction_type') === InteractionType::HUMAN_SUPPORT ? now() : null,
            'last_activity_at'  => now(),
            'customer_payload'  => [
                'AccountSid' => data_get($this->payload, 'AccountSid'),
                'From'       => $this->getCustomerChannelId(),
            ],
        ]);

        $this->existMessage = TitanOperatorHistory::query()
            ->where('conversation_id', $this->conversation->getKey())
            ->exists();

        $this->conversation->setRelation('titan_operator', $this->titan_operator);

        return $this->conversation;
    }

    public function getCustomerChannelId(): ?string
    {
        return data_get($this->payload, 'From');
    }

    public function getTitanOperatorId(): int
    {
        return $this->operatorId;
    }

    public function setTitanOperatorId(int $operatorId): self
    {
        $this->operatorId = $operatorId;

        return $this;
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

    public function setIpAddress(?int $ipAddress = null): self
    {
        if ($ipAddress) {
            $this->ipAddress = $ipAddress;
        } else {
            $this->ipAddress = request()?->header('cf-connecting-ip') ?? request()?->ip();
        }

        return $this;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function setPayload(?array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function getTitanOperator(): Model|Builder|TitanOperator|null
    {
        return $this->titan_operator;
    }
}
