<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\Webhook;

use App\Extensions\MarketingBot\System\Services\TitanTalk\Channels\GenericInboundChannelService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenericChannelWebhookController extends Controller
{
    public function __construct(protected GenericInboundChannelService $service)
    {
    }

    public function __invoke(Request $request, string $channel): JsonResponse
    {
        abort_unless(in_array($channel, ['email', 'sms'], true), 404);

        return response()->json(
            $this->service->handle($channel, $request->all())
        );
    }
}
