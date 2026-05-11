<?php

namespace App\Extensions\TitanOperator\System\Channels\Whatsapp\Http\Controllers;

use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorChannelResource;
use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use App\Extensions\TitanOperator\System\Channels\Whatsapp\Http\Requests\WhatsappChannelStoreRequest;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TitanOperatorWhatsappController extends Controller
{
    public function store(WhatsappChannelStoreRequest $request): TitanOperatorChannelResource|JsonResponse
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

        return TitanOperatorChannelResource::make($item)->additional([
            'status'  => 'success',
            'message' => trans('TitanOperator channel successfully created'),
        ]);
    }
}
