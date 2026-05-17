<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Modules\TitanEchoAssist\Models\Chatbot;

class PortalSessionController extends PortalBaseController
{
    public function show(Chatbot $chatbot): JsonResponse
    {
        return response()->json([
            'chatbot' => [
                'id' => $chatbot->getKey(),
                'uuid' => $chatbot->uuid,
                'title' => $chatbot->title,
                'welcome_message' => $chatbot->welcome_message,
                'bubble_message' => $chatbot->bubble_message,
            ],
            'branding' => [
                'logo' => $chatbot->logo,
                'avatar' => $chatbot->avatar,
                'color' => $chatbot->color,
                'position' => $chatbot->position,
            ],
        ]);
    }
}
