<?php

namespace App\Support\GroundZero;

use Illuminate\Support\Facades\App;
use Modules\TitanZero\Services\TitanZeroQueryService;
use Throwable;

class TitanAiRuntime
{
    public function __construct(
        protected CommandCatalog $catalog,
    ) {}

    /**
     * Route GroundZero chat through TitanZero when installed, using TitanCore through
     * TitanZero's own service bindings. Falls back to the local command catalog so
     * GroundZero still loads safely when AI modules are not installed yet.
     *
     * @return array{intent:string,title:string,message:string,snapshot:array,engine:string,ok:bool,meta:array}
     */
    public function respond(string $prompt, ?int $organizationId, mixed $user = null): array
    {
        $context = [
            'company_id' => $organizationId,
            'organization_id' => $organizationId,
            'user_id' => $user?->id,
            'source' => 'groundzero.panel',
            'surface' => 'groundzero',
            'role' => 'business_operator',
            'boundaries' => [
                'groundzero' => 'chat-first business operating panel',
                'titanpro' => 'standard admin, tables, dispatch, records, management',
                'titango' => 'cleaner field PWA only',
            ],
        ];

        if (class_exists(TitanZeroQueryService::class) && App::bound(TitanZeroQueryService::class)) {
            try {
                $result = App::make(TitanZeroQueryService::class)->query($context, $prompt);

                return [
                    'intent' => $this->intentFromPrompt($prompt),
                    'title' => ($result['ok'] ?? false) ? 'TitanZero response' : 'TitanZero unavailable',
                    'message' => (string) ($result['content'] ?? 'TitanZero returned no response.'),
                    'snapshot' => $this->catalog->snapshot($organizationId),
                    'engine' => 'TitanZero + TitanCore',
                    'ok' => (bool) ($result['ok'] ?? false),
                    'meta' => [
                        'model' => $result['model'] ?? null,
                        'latency_ms' => $result['latency_ms'] ?? null,
                        'aegis_verdict' => $result['aegis_verdict'] ?? null,
                        'escalation_required' => $result['escalation_required'] ?? false,
                        'error' => $result['error'] ?? null,
                    ],
                ];
            } catch (Throwable $e) {
                report($e);

                return $this->fallback($prompt, $organizationId, 'TitanZero fallback', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->fallback($prompt, $organizationId, 'Local fallback', [
            'reason' => 'TitanZeroQueryService not installed or not bound',
        ]);
    }

    public function integrationStatus(): array
    {
        return [
            'titanzero_present' => class_exists(TitanZeroQueryService::class),
            'titanzero_bound' => class_exists(TitanZeroQueryService::class) && App::bound(TitanZeroQueryService::class),
            'titancore_present' => class_exists('Modules\\TitanCore\\Providers\\TitanCoreServiceProvider'),
            'engine' => (class_exists(TitanZeroQueryService::class) && App::bound(TitanZeroQueryService::class))
                ? 'TitanZero + TitanCore'
                : 'Local fallback',
        ];
    }

    private function fallback(string $prompt, ?int $organizationId, string $title, array $meta = []): array
    {
        $response = $this->catalog->commandResponse($prompt, $organizationId);
        $response['title'] = $title.': '.$response['title'];
        $response['engine'] = 'Local fallback';
        $response['ok'] = true;
        $response['meta'] = $meta;

        return $response;
    }

    private function intentFromPrompt(string $prompt): string
    {
        $normalised = str($prompt)->lower()->toString();

        return match (true) {
            str_contains($normalised, 'today'), str_contains($normalised, 'schedule') => 'daily_briefing',
            str_contains($normalised, 'assign'), str_contains($normalised, 'unassigned') => 'assignment_review',
            str_contains($normalised, 'invoice'), str_contains($normalised, 'money'), str_contains($normalised, 'finance') => 'finance_review',
            str_contains($normalised, 'quality'), str_contains($normalised, 'check') => 'quality_review',
            default => 'groundzero_chat',
        };
    }
}
