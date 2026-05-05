<?php

return [
    'key' => 'titan-chatbot',
    'name' => 'TitanChatbot',
    'enabled' => true,
    'tenant_safe' => true,
    'primary_model' => Modules\TitanChatbot\Models\Chatbot::class,
    'service' => Modules\TitanChatbot\Services\TitanChatbotService::class,
    'service_contract' => Modules\TitanChatbot\Services\Contracts\TitanChatbotServiceContract::class,
    'agent_binding_service' => Modules\TitanChatbot\Services\ModuleAgentBindingService::class,
    'agent_control_service' => Modules\TitanChatbot\Services\ModuleAgentControlService::class,
    'capabilities' => [
        'external_chatbot_builder', 'rag_training', 'frontend_widget', 'whatsapp', 'telegram',
        'messenger', 'voice', 'agent_handoff', 'analytics', 'multi_tenant', 'billing_meters',
        'workflow_engine', 'search', 'pwa_ready'
    ],
    'providers' => [
        Modules\TitanChatbot\Providers\ModuleServiceProvider::class,
        Modules\TitanChatbot\Providers\RouteServiceProvider::class,
        Modules\TitanChatbot\Providers\EventServiceProvider::class,
        Modules\TitanChatbot\Providers\AuthServiceProvider::class,
        Modules\TitanChatbot\Providers\FilamentServiceProvider::class,
    ],
];
