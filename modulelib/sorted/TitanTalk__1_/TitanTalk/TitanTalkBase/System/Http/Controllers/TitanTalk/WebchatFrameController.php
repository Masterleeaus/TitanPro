<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class WebchatFrameController extends Controller
{
    public function __invoke(): View
    {
        return view('titantalk::frontend/frame', [
            'titantalkWebchatEnabled' => (bool) config('titantalk-webchat.enabled', true),
        ]);
    }
}
