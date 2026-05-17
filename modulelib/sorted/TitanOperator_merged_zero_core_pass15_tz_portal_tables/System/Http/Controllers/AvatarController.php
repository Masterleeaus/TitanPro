<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers;

use App\Extensions\TitanOperator\System\Http\Requests\AvatarRequest;
use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorAvatarResource;
use App\Extensions\TitanOperator\System\Models\TitanOperatorAvatar;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;

class AvatarController extends Controller
{
    public function __invoke(AvatarRequest $request)
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $file = $request->file('avatar')->store('avatars', ['disk' => 'public']);

        $operatorAvatar = TitanOperatorAvatar::query()->create([
            'user_id' => $request->user()->getAttribute('id'),
            'avatar'  => 'uploads/' . $file,
        ]);

        return TitanOperatorAvatarResource::make($operatorAvatar);
    }
}
