<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Requests\TitanZero\ZeroChatRequest;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Assistants\TitanZeroAssistant;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Engines\TitanZeroEngine;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Registry\TitanToolRegistry;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Systems\TitanZeroSystem;

class TitanZeroApiController extends Controller
{
    public function __construct(
        protected TitanZeroEngine $engine,
        protected TitanZeroAssistant $assistant,
        protected TitanToolRegistry $tools,
        protected TitanZeroSystem $system,
    ) {}

    public function status()
    {
        return response()->json([
            'ok' => true,
            'route_prefix' => config('titan_operator.zero.route.prefix'),
            'team_id' => auth()->user()?->team_id,
            'readiness' => config('titan_operator.zero.readiness', []),
        ]);
    }

    public function think(ZeroChatRequest $request)
    {
        return response()->json($this->engine->think(
            $request->string('intent')->toString(),
            $request->input('payload', [])
        ));
    }

    public function chat(ZeroChatRequest $request)
    {
        return response()->json($this->assistant->respond(
            $request->string('intent')->toString(),
            $request->input('payload', [])
        ));
    }

    public function tools()
    {
        return response()->json(['tools' => $this->tools->grouped()]);
    }

    public function preview()
    {
        return response()->json($this->system->dashboard());
    }

    public function surfacePreview(string $surface)
    {
        return response()->json(match (strtolower($surface)) {
            'boss' => $this->system->bossDashboard(),
            'go' => $this->system->goDashboard(),
            default => ['ok' => false, 'message' => 'Unknown surface'],
        });
    }

    public function timeline()
    {
        return response()->json(['audit' => $this->system->recentAudit(auth()->user()?->team_id)]);
    }

    public function pwaBootstrap(Request $request): JsonResponse
    {
        $nodeId = (string) $request->string('node_id')->toString();
        $user = $request->user();

        $payload = [
            'ok' => true,
            'node_id' => $nodeId !== '' ? $nodeId : null,
            'team_id' => $user?->team_id,
            'user_id' => $user?->id,
            'route_prefix' => config('titan_operator.zero.route.prefix'),
            'api_prefix' => config('titan_operator.zero.api.prefix'),
            'runtime' => [
                'sync_endpoint' => route('dashboard.user.titanzero.api.signals.ingest'),
                'blob_endpoint' => route('dashboard.user.titanzero.api.pwa.blobs.ingest'),
                'handshake_endpoint' => route('dashboard.user.titanzero.api.pwa.handshake'),
                'booted_at' => now()->toIso8601String(),
            ],
            'readiness' => config('titan_operator.zero.readiness', []),
        ];

        $this->system->logAudit($user?->team_id, $user?->id, 'zero.runtime.bootstrap', [
            'node_id' => $payload['node_id'],
            'route_prefix' => $payload['route_prefix'],
        ]);

        return response()->json($payload);
    }

    public function pwaHandshake(Request $request): JsonResponse
    {
        $input = $request->validate([
            'node_id' => 'required|string|max:191',
            'node_origin' => 'nullable|string|max:50',
            'trust_level' => 'nullable|string|max:50',
            'device_label' => 'nullable|string|max:255',
            'platform' => 'nullable|string|max:100',
            'app_version' => 'nullable|string|max:50',
        ]);

        $user = $request->user();
        $nodeId = $input['node_id'];
        $trust = \App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support\NodeTrust::normalize($input['trust_level'] ?? 'standard');

        $payload = [
            'ok' => true,
            'node_id' => $nodeId,
            'node_secret' => hash('sha256', $nodeId . '|' . ($user?->team_id ?? 'guest') . '|' . config('app.key')),
            'node_origin' => $input['node_origin'] ?? 'pwa',
            'trust_level' => $trust,
            'device_label' => $input['device_label'] ?? null,
            'platform' => $input['platform'] ?? null,
            'app_version' => $input['app_version'] ?? null,
            'handshake_signal' => \App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support\FederationHandshake::build([
                'node_id' => $nodeId,
                'node_origin' => $input['node_origin'] ?? 'pwa',
                'node_trust' => $trust,
            ]),
            'issued_at' => now()->toIso8601String(),
        ];

        $this->system->logAudit($user?->team_id, $user?->id, 'zero.runtime.handshake', [
            'node_id' => $nodeId,
            'trust_level' => $trust,
            'platform' => $input['platform'] ?? null,
        ]);

        return response()->json($payload);
    }

    public function ingestSignals(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'node_id' => 'nullable|string|max:191',
            'app_version' => 'nullable|string|max:50',
            'signals' => 'required|array|min:1',
            'signals.*.signal_key' => 'required|string|max:191',
            'signals.*.signal_stage' => 'nullable|string|max:100',
            'signals.*.signal_status' => 'nullable|string|max:100',
            'signals.*.payload' => 'nullable|array',
            'signals.*.timestamp' => 'nullable|string|max:100',
            'signals.*.client_created_at' => 'nullable|string|max:100',
        ]);

        $user = $request->user();
        $accepted = [];
        foreach ($validated['signals'] as $index => $signal) {
            $accepted[] = [
                'index' => $index,
                'signal_key' => $signal['signal_key'],
                'signal_stage' => $signal['signal_stage'] ?? \App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support\SignalRegistry::stageFor($signal['signal_key'], 'signal'),
                'signal_status' => 'accepted',
                'timestamp' => $signal['timestamp'] ?? now()->toIso8601String(),
            ];
        }

        $this->system->logAudit($user?->team_id, $user?->id, 'zero.runtime.signals.ingested', [
            'node_id' => $validated['node_id'] ?? null,
            'count' => count($accepted),
            'app_version' => $validated['app_version'] ?? null,
            'signals' => array_column($accepted, 'signal_key'),
        ]);

        return response()->json([
            'ok' => true,
            'accepted' => $accepted,
            'accepted_count' => count($accepted),
            'synced_at' => now()->toIso8601String(),
        ]);
    }

    public function pwaBlobIngest(Request $request): JsonResponse
    {
        $request->validate([
            'node_id' => 'nullable|string|max:191',
            'files' => 'required|array|min:1',
            'files.*' => 'file|max:20480',
            'meta' => 'nullable|array',
        ]);

        $accepted = [];
        foreach ($request->file('files', []) as $index => $file) {
            $meta = $request->input("meta.$index", []);
            $accepted[] = [
                'id' => $meta['id'] ?? ('blob_' . $index),
                'name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'byte_size' => $file->getSize(),
                'captured_at' => $meta['captured_at'] ?? now()->toIso8601String(),
            ];
        }

        $user = $request->user();
        $this->system->logAudit($user?->team_id, $user?->id, 'zero.runtime.blobs.ingested', [
            'node_id' => $request->input('node_id'),
            'accepted_count' => count($accepted),
        ]);

        return response()->json([
            'ok' => true,
            'accepted' => $accepted,
            'accepted_count' => count($accepted),
        ]);
    }

    public function updateWriting(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'prompt' => 'required|string|max:4000',
            'content' => 'required|string',
            'language' => 'nullable|string|max:20',
        ]);

        $response = $this->assistant->respond('writing.assist', [
            'prompt' => $payload['prompt'],
            'content' => $payload['content'],
            'language' => $payload['language'] ?? null,
        ]);

        $result = data_get($response, 'message')
            ?? data_get($response, 'output')
            ?? data_get($response, 'text')
            ?? $payload['content'];

        return response()->json([
            'ok' => true,
            'result' => is_string($result) ? $result : json_encode($result),
        ]);
    }

}
