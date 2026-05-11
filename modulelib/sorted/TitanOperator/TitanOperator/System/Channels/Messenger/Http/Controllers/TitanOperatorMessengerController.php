<?php

namespace App\Extensions\TitanOperator\System\Channels\Messenger\Http\Controllers;

use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorChannelResource;
use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use App\Extensions\TitanOperator\System\Channels\Messenger\Http\Requests\MessengerChannelStoreRequest;
use App\Extensions\TitanOperator\System\Channels\Messenger\Services\MessengerService;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class TitanOperatorMessengerController extends Controller
{
    public function store(MessengerChannelStoreRequest $request): TitanOperatorChannelResource|JsonResponse
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
            app(MessengerService::class)->setTitanOperatorChannel($item);
        } catch (Exception $exception) {
            $item->delete();

            return response()->json([
                'status'  => 'error',
                'message' => trans('Messenger channel not connected'),
            ]);
        }

        return TitanOperatorChannelResource::make($item)->additional([
            'status'  => 'success',
            'message' => trans('Messenger channel successfully created'),
        ]);
    }
}
