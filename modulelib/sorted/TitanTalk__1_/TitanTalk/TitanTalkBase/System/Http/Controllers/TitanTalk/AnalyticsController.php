<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk;

use App\Extensions\MarketingBot\System\Analytics\TitanTalkAnalyticsService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class AnalyticsController extends Controller
{
    public function __invoke(TitanTalkAnalyticsService $analyticsService): View
    {
        $analytics = $analyticsService->overview(auth()->id());

        return view('titantalk::analytics.index', compact('analytics'));
    }
}
