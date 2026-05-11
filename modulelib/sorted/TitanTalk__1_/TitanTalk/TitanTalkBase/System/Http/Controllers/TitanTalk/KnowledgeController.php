<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk;

use App\Extensions\MarketingBot\System\Services\TitanTalk\KnowledgeOverviewService;
use Illuminate\Routing\Controller;

class KnowledgeController extends Controller
{
    public function __invoke(KnowledgeOverviewService $knowledgeOverviewService)
    {
        return view('titantalk::knowledge.index', [
            'overview' => $knowledgeOverviewService->overview(),
        ]);
    }
}
