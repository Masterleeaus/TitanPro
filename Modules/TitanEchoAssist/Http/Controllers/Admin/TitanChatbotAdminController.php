<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Modules\TitanEchoAssist\Services\TitanChatbotService;

class TitanChatbotAdminController extends Controller
{
    public function __construct(private readonly TitanChatbotService $service) {}

    public function index(): mixed
    {
        $payload = $this->service->healthSummary() + ['surface' => 'admin'];
        return function_exists('response') ? response()->json($payload) : $payload;
    }
}
