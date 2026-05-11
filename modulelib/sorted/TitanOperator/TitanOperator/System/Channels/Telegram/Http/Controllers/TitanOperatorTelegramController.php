<?php

namespace App\Extensions\TitanOperator\System\Channels\Telegram\Http\Controllers;

use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorChannelResource;
use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use App\Extensions\TitanOperator\System\Channels\Telegram\Http\Requests\TelegramChannelStoreRequest;
use App\Extensions\TitanOperator\System\Channels\Telegram\Services\Telegram\TelegramService;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class TitanOperatorTelegramController extends Controller
{
    public function store(TelegramChannelStoreRequest $request): TitanOperatorChannelResource|JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'status'  => 'error',
                'message' => trans('This feature is disabled in demo mode.'),
            ]);
        }

        $item = TitanOperatorChannel::query()->create(
            $request->validated()
        );

        try {
            app(TelegramService::class)->setChannel($item)->setWebhook();
        } catch (Exception $exception) {
            $item->delete();

            return response()->json([
                'status'  => 'error',
                'message' => trans('Telegram channel not connected'),
            ]);
        }

        return TitanOperatorChannelResource::make($item)->additional([
            'status'  => 'success',
            'message' => trans('TitanOperator channel successfully created'),
        ]);
    }
}
