<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers;

use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorChannelResource;
use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class TitanOperatorMultiChannelController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $items = TitanOperatorChannel::query()
            ->where('user_id', Auth::id())
            ->where('operator_id', $request['operator_id'])
            ->get();

        return TitanOperatorChannelResource::collection($items)->additional([
            'status' => 'success',
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'status'  => 'error',
                'message' => trans('This feature is disabled in demo mode.'),
            ]);
        }

        $channel = TitanOperatorChannel::query()
            ->where('user_id', Auth::id())
            ->findOrFail($request['channel_id']);

        $channel->delete();

        return response()->json([
            'status'  => 'success',
            'message' => trans('TitanOperator channel successfully deleted'),
        ]);
    }
}
