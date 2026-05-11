<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk;

use App\Extensions\MarketingBot\System\Models\MarketingCampaignEmbedding;
use Illuminate\Support\Collection;

class KnowledgeOverviewService
{
    /**
     * @return array<string,mixed>
     */
    public function overview(): array
    {
        $query = MarketingCampaignEmbedding::query();

        $total = (clone $query)->count();
        $trained = (clone $query)->whereNotNull('trained_at')->count();
        $byType = (clone $query)
            ->selectRaw('type, count(*) as aggregate')
            ->groupBy('type')
            ->pluck('aggregate', 'type')
            ->toArray();

        $recent = (clone $query)
            ->latest('id')
            ->limit(20)
            ->get(['id', 'title', 'type', 'url', 'file', 'trained_at']);

        return [
            'total' => $total,
            'trained' => $trained,
            'pending' => max(0, $total - $trained),
            'by_type' => $byType,
            'recent' => $recent,
        ];
    }
}
