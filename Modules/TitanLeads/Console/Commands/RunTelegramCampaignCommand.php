<?php

namespace Modules\TitanLeads\Console\Commands;

use Modules\TitanLeads\Enums\CampaignStatus;
use Modules\TitanLeads\Enums\CampaignType;
use Modules\TitanLeads\Models\MarketingCampaign;
use Modules\TitanLeads\Services\Telegram\TelegramSenderService;
use Exception;
use Illuminate\Console\Command;

class RunTelegramCampaignCommand extends Command
{
    protected $signature = 'app:run-telegram-campaign';

    protected $description = 'Run a new Telegram campaign';

    public function handle()
    {
        $now = now();

        $telegramSenderService = app(TelegramSenderService::class);

        $campaigns = MarketingCampaign::query()
            ->where('type', CampaignType::telegram)
            ->where('status', CampaignStatus::scheduled)
            ->where('scheduled_at', '<=', $now)
            ->get();

        MarketingCampaign::query()
            ->where('type', CampaignType::telegram)
            ->where('status', CampaignStatus::scheduled)
            ->where('scheduled_at', '<=', $now)
            ->update([
                'status' => CampaignStatus::running,
            ]);

        $campaigns->map(function (MarketingCampaign $item) use ($telegramSenderService) {
            /**
             * @var MarketingCampaign $item
             */
            try {
                $telegramSenderService->setMarketingCampaign($item)->send();
            } catch (Exception $exception) {
            }
        });
    }
}
