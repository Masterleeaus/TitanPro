<?php

namespace App\Extensions\TitanLeads\Modules\TitanNexus\Console\Commands;

use App\Extensions\TitanLeads\Modules\TitanNexus\Enums\CampaignStatus;
use App\Extensions\TitanLeads\Modules\TitanNexus\Enums\CampaignType;
use App\Extensions\TitanLeads\Modules\TitanNexus\Models\MarketingCampaign;
use App\Extensions\TitanLeads\Modules\TitanNexus\Services\Whatsapp\WhatsappSenderService;
use Exception;
use Illuminate\Console\Command;

class RunWhatsappCampaignCommand extends Command
{
    protected $signature = 'app:run-whatsapp-campaign';

    protected $description = 'Run a new Whatsapp campaign';

    public function handle()
    {
        $now = now();

        $whatsappService = app(WhatsappSenderService::class);

        $campaigns = MarketingCampaign::query()
            ->where('type', CampaignType::whatsapp)
            ->where('status', CampaignStatus::scheduled)
            ->where('scheduled_at', '<=', $now)
            ->get();

        $campaigns->map(function (MarketingCampaign $campaign) use ($whatsappService) {
            try {
                $whatsappService
                    ->setMarketingCampaign($campaign)
                    ->send();
            } catch (Exception $e) {
            }
        });
    }
}
