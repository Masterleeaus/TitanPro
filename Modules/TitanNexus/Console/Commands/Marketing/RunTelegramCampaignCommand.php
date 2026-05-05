<?php

namespace App\Extensions\TitanLeads\Modules\TitanNexus\Console\Commands;

use App\Extensions\TitanLeads\Modules\TitanNexus\Enums\CampaignStatus;
use App\Extensions\TitanLeads\Modules\TitanNexus\Enums\CampaignType;
use App\Extensions\TitanLeads\Modules\TitanNexus\Models\MarketingCampaign;
use App\Extensions\TitanLeads\Modules\TitanNexus\Services\Telegram\TelegramSenderService;
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
