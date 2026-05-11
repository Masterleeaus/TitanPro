<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Telegram;

use App\Extensions\TitanLeads\System\Models\Telegram\TelegramGroupSubscriber;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TelegramSubscriberController extends Controller
{
    public function index()
    {
        $items = TelegramGroupSubscriber::query()
            ->where('user_id', Auth::id())
            ->get();

        return view('titan-leads::telegram-subscriber.index', [
            'items' => $items,
        ]);
    }

    public function destroy(TelegramGroupSubscriber $telegramSubscriber): JsonResponse
    {
        $telegramSubscriber->delete();

        return response()->json([
            'status'  => 'success',
            'message' => __('Contact deleted successfully'),
        ]);
    }
}
