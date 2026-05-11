<?php

namespace App\Extensions\TitanOperator\System\Voice\Http\Controllers;

use App\Extensions\TitanOperator\System\Voice\Http\Requests\AvatarRequest;
use App\Extensions\TitanOperator\System\Voice\Http\Resources\TitanOperatorAvatarResource;
use App\Extensions\TitanOperator\System\Voice\Models\ExtVoiceoperatorAvatar;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AvatarController extends Controller
{
    /**
     * upload custom avatar for voice titan_operator
     */
    public function __invoke(AvatarRequest $request): JsonResource|JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $file = $request->file('avatar')->store('avatars', ['disk' => 'public']);

        $operatorAvatar = ExtVoiceoperatorAvatar::query()->create([
            'user_id' => $request->user()->getAttribute('id'),
            'avatar'  => 'uploads/' . $file,
        ]);

        return TitanOperatorAvatarResource::make($operatorAvatar);
    }
}
