<?php

namespace Modules\TitanLeads\Console\Commands;

use Modules\TitanLeads\Enums\CampaignStatus;
use Modules\TitanLeads\Enums\CampaignType;
use Modules\TitanLeads\Models\MarketingCampaign;
use Modules\TitanLeads\Services\Telegram\TelegramSenderService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunTelegramCampaignCommand extends Command
{
    protected $signature = 'titan-leads:run-telegram-campaign';

    protected $description = 'Run a new Telegram campaign';

    public function handle(): int
    {
        $now = now();

        $telegramSenderService = app(TelegramSenderService::class);

        // Fetch and immediately mark as running (atomic batch to prevent double-sends)
        $campaigns = MarketingCampaign::query()
            ->where('type', CampaignType::telegram)
            ->where('status', CampaignStatus::scheduled)
            ->where('scheduled_at', '<=', $now)
            ->get();

        MarketingCampaign::query()
            ->whereIn('id', $campaigns->modelKeys())
            ->update(['status' => CampaignStatus::running]);

        foreach ($campaigns as $item) {
            try {
                $telegramSenderService->setMarketingCampaign($item)->send();
            } catch (Exception $exception) {
                Log::error('[TitanLeads] Telegram campaign failed', [
                    'campaign_id' => $item->getKey(),
                    'error'       => $exception->getMessage(),
                ]);

                $item->update(['status' => CampaignStatus::failed]);
            }
        }

        return self::SUCCESS;
    }
}
