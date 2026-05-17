<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Services\TitanChatbotService;

class TitanChatbotApiController extends Controller
{
    public function __construct(private readonly TitanChatbotService $service) {}

    public function index(): mixed
    {
        $payload = $this->service->healthSummary();
        return function_exists('response') ? response()->json($payload) : $payload;
    }

    public function health(): mixed
    {
        $payload = $this->service->healthSummary();
        return function_exists('response') ? response()->json($payload) : $payload;
    }

    public function show(string $uuid): JsonResponse
    {
        $chatbot = Chatbot::query()
            ->where('uuid', $uuid)
            ->where('active', true)
            ->firstOrFail();

        return response()->json([
            'data' => [
                'uuid' => $chatbot->uuid,
                'title' => $chatbot->title,
                'bubble_design' => $chatbot->bubble_design?->value ?? $chatbot->bubble_design ?? 'modern',
                'color_mode' => $chatbot->color_mode?->value ?? $chatbot->color_mode ?? 'solid',
                'header_bg' => $chatbot->header_bg?->value ?? $chatbot->header_bg ?? ($chatbot->header_bg_type ?: 'color'),
                'position' => $chatbot->position?->value ?? $chatbot->position ?? 'right',
                'trigger_background' => $chatbot->trigger_background ?: '#2563eb',
                'trigger_foreground' => $chatbot->trigger_foreground ?: '#ffffff',
                'avatar' => $chatbot->avatar_url,
                'bubble_message' => $chatbot->bubble_message,
                'promo_banner' => [
                    'image' => $chatbot->promo_banner_image,
                    'title' => $chatbot->promo_banner_title,
                    'description' => $chatbot->promo_banner_description,
                    'cta_label' => $chatbot->promo_banner_cta_label,
                    'cta_url' => $chatbot->promo_banner_cta_url,
                ],
            ],
        ]);
    }
}
