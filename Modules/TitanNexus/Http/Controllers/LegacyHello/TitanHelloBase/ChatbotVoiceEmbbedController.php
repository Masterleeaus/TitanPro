<?php

namespace Extensions\TitanNexus\Controllers;

use Extensions\TitanNexus\Models\ExtVoiceChatbot;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatbotVoiceEmbbedController extends Controller
{
    /**
     * detail of external voice chatbot information by uuid
     */
    public function index(string|int $uuid): JsonResource
    {
        $ExtVoiceChatbot = ExtVoiceChatbot::where('uuid', $uuid)->first();

        return JsonResource::make($ExtVoiceChatbot);
    }
}
