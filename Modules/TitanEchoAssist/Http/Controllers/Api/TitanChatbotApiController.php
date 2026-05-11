<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api;

use Illuminate\Routing\Controller;
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
}
