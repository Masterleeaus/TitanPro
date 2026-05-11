<?php

namespace App\Extensions\TitanOperator\System\Services\ClientPortal;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Services\TitanOperatorService;
use App\Helpers\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientPortalBuilderDataService
{
    public function __construct(
        protected TitanOperatorService $service,
        protected ClientPortalInboxService $inboxService,
        protected ClientPortalTemplateService $templateService,
        protected ClientPortalStepCatalog $stepCatalog,
        protected ClientPortalRuntimeHealthService $runtimeHealthService,
        protected ClientPortalRuntimeConfigService $runtimeConfigService,
    ) {}

    public function dashboardPayload(Request $request): array
    {
        if (method_exists(Helper::class, 'appIsDemoForTitanOperator') && Helper::appIsDemoForTitanOperator()) {
            TitanOperator::query()->where('is_demo', '=', 0)->where('created_at', '<', now()->subMinutes(30))->delete();
        }

        $externalTitanOperators = $request->user()->externalTitanOperators->pluck('id')->toArray();
        $selectedTemplate = $this->templateService->selectedTemplate($request->string('template')->toString());
        $builderSteps = $this->stepCatalog->steps();

        return [
            'titan_operators' => $this->service->query()
                ->with('channels:id,operator_id,channel')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(perPage: 100),
            'avatars' => $this->service->avatars(),
            'unreadAgentMessagesCount' => $this->service->unreadAgentMessagesCount($externalTitanOperators),
            'unreadAiBotMessagesCount' => $this->service->unreadAiBotMessagesCount($externalTitanOperators),
            'allMessagesCount' => $this->service->allMessagesCount($externalTitanOperators),
            'portalStats' => $this->inboxService->stats(),
            'recentConversations' => $this->inboxService->recentConversations(),
            'channelBreakdown' => $this->inboxService->channelBreakdown(),
            'portalTemplates' => $this->templateService->templates(),
            'selectedPortalTemplate' => $selectedTemplate,
            'builderSteps' => $builderSteps,
            'runtimeChecks' => $this->runtimeHealthService->checks(data_get($selectedTemplate, 'slug')),
            'installChecklist' => [
                'Choose the client-portal template that best matches the workflow.',
                'Run the existing chatbot builder steps: configure, customize, train, embed, and channel.',
                'Open preview to verify the runtime shell, install prompt, and iframe flow.',
                'Push live by copying embed code or installing the shell on mobile/desktop.',
            ],
            'clientPortalRuntime' => array_merge(
                $this->runtimeConfigService->config(data_get($selectedTemplate, 'slug')),
                ['step_count' => $builderSteps->count()]
            ),
        ];
    }

    public function embedPayload(Request $request, TitanOperator $titan_operator): array
    {
        $iframeUrl = route('titan_operator.frame', $titan_operator->getAttribute('uuid'));
        $embedCode = '<iframe src="' . e($iframeUrl) . '" style="width:100%;height:700px;border:0;border-radius:12px;overflow:hidden;" loading="lazy"></iframe>';

        return array_merge($this->dashboardPayload($request), [
            'titan_operator' => $titan_operator,
            'iframeUrl' => $iframeUrl,
            'embedCode' => $embedCode,
        ]);
    }

    public function installPayload(Request $request, ?TitanOperator $titan_operator = null): array
    {
        $payload = $this->dashboardPayload($request);
        $payload['titan_operator'] = $titan_operator;

        return $payload;
    }

    public function portalStatusPayload(Request $request): array
    {
        $payload = $this->dashboardPayload($request);

        return [
            'status' => 'ok',
            'runtime' => $payload['clientPortalRuntime'],
            'stats' => $payload['portalStats'],
            'recent_conversations' => $payload['recentConversations'],
            'channel_breakdown' => $payload['channelBreakdown'],
            'templates' => $payload['portalTemplates'],
            'selected_template' => $payload['selectedPortalTemplate'],
            'builder_steps' => $payload['builderSteps'],
            'runtime_checks' => $payload['runtimeChecks'],
        ];
    }

    public function templatesPayload(Request $request): array
    {
        $payload = $this->dashboardPayload($request);

        return [
            'templates' => $payload['portalTemplates'],
            'selected_template' => $payload['selectedPortalTemplate'],
            'builder_steps' => $payload['builderSteps'],
            'runtime' => $payload['clientPortalRuntime'],
        ];
    }
}
