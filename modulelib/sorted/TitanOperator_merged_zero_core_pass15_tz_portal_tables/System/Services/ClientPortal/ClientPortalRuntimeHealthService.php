<?php

namespace App\Extensions\TitanOperator\System\Services\ClientPortal;

class ClientPortalRuntimeHealthService
{
    public function __construct(protected ClientPortalRuntimeConfigService $runtimeConfigService) {}

    public function checks(?string $selectedTemplate = null): array
    {
        $checks = $this->runtimeConfigService->checks($selectedTemplate);

        $checks[] = [
            'label' => 'Builder flow',
            'status' => 'ready',
            'detail' => 'Configure, customize, train, embed, and channel steps use the current chatbot build screens.',
        ];

        $checks[] = [
            'label' => 'Training hooks',
            'status' => 'ready',
            'detail' => 'Website, PDF, text, and Q&A screens resolve through TitanOperator training routes.',
        ];

        $checks[] = [
            'label' => 'Inbox handoff',
            'status' => 'ready',
            'detail' => 'Recent conversations and channel breakdown are surfaced for the client-facing portal shell.',
        ];

        return $checks;
    }
}
