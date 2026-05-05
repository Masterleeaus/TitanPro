<?php

namespace Modules\TitanChatbot\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\TitanChatbot\Services\TitanChatbotService;

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
}
