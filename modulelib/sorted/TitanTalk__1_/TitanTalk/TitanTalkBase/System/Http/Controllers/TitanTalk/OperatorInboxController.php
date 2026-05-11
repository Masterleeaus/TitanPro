<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class OperatorInboxController extends Controller
{
    public function index(Request $request)
    {
        return view('titantalk::operator.index');
    }

    public function notification(Request $request): JsonResponse
    {
        $count = MarketingConversation::query()
            ->whereHas('histories', function ($query) {
                $query->whereNull('read_at');
            })
            ->where('user_id', Auth::id())
            ->count();

        return response()->json([
            'class' => 'hidden',
            'count' => $count,
            'status' => 'success',
        ]);
    }
}
