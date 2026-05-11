<?php

return [
    'key' => 'titan-chatbot',
    'name' => 'TitanChatbot',
    'enabled' => true,
    'tenant_safe' => true,
    'primary_model' => Modules\TitanEchoAssist\Models\Chatbot::class,
    'service' => Modules\TitanEchoAssist\Services\TitanChatbotService::class,
    'service_contract' => Modules\TitanEchoAssist\Services\Contracts\TitanChatbotServiceContract::class,
    'agent_binding_service' => Modules\TitanEchoAssist\Services\ModuleAgentBindingService::class,
    'agent_control_service' => Modules\TitanEchoAssist\Services\ModuleAgentControlService::class,
    'capabilities' => [
        'external_chatbot_builder', 'rag_training', 'frontend_widget', 'whatsapp', 'telegram',
        'messenger', 'voice', 'agent_handoff', 'analytics', 'multi_tenant', 'billing_meters',
        'workflow_engine', 'search', 'pwa_ready'
    ],
    'providers' => [
        Modules\TitanEchoAssist\Providers\ModuleServiceProvider::class,
        Modules\TitanEchoAssist\Providers\RouteServiceProvider::class,
        Modules\TitanEchoAssist\Providers\EventServiceProvider::class,
        Modules\TitanEchoAssist\Providers\AuthServiceProvider::class,
        Modules\TitanEchoAssist\Providers\FilamentServiceProvider::class,
    ],
];
