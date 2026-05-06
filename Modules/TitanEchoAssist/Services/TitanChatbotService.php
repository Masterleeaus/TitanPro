<?php

namespace Modules\TitanChatbot\Services;

use Modules\TitanChatbot\Services\Contracts\TitanChatbotServiceContract;

class TitanChatbotService implements TitanChatbotServiceContract
{
    public function moduleKey(): string
    {
        return 'titan-chatbot';
    }

    public function enabled(): bool
    {
        return function_exists('config') ? (bool) config('titan-chatbot.enabled', true) : true;
    }

    public function capabilities(): array
    {
        $defaults = [
            'external_chatbot_builder', 'rag_training', 'frontend_widget', 'whatsapp', 'telegram',
            'messenger', 'voice', 'agent_handoff', 'analytics', 'multi_tenant', 'billing_meters',
            'workflow_engine', 'search', 'pwa_ready'
        ];

        return function_exists('config') ? (array) config('module.capabilities', $defaults) : $defaults;
    }

    public function healthSummary(): array
    {
        return [
            'ok' => $this->enabled(),
            'module' => 'TitanChatbot',
            'key' => $this->moduleKey(),
            'capabilities' => $this->capabilities(),
        ];
    }
}
