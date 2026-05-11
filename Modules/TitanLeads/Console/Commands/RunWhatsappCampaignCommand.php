<?php

namespace Modules\TitanLeads\Console\Commands;

use Modules\TitanLeads\Enums\CampaignStatus;
use Modules\TitanLeads\Enums\CampaignType;
use Modules\TitanLeads\Models\MarketingCampaign;
use Modules\TitanLeads\Services\Whatsapp\WhatsappSenderService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunWhatsappCampaignCommand extends Command
{
    protected $signature = 'titan-leads:run-whatsapp-campaign';

    protected $description = 'Run a new WhatsApp campaign';

    public function handle(): int
    {
        $now = now();

        $whatsappService = app(WhatsappSenderService::class);

        $campaigns = MarketingCampaign::query()
            ->where('type', CampaignType::whatsapp)
            ->where('status', CampaignStatus::scheduled)
            ->where('scheduled_at', '<=', $now)
            ->get();

        foreach ($campaigns as $campaign) {
            try {
                $whatsappService
                    ->setMarketingCampaign($campaign)
                    ->send();
            } catch (Exception $e) {
                Log::error('[TitanLeads] WhatsApp campaign failed', [
                    'campaign_id' => $campaign->getKey(),
                    'error'       => $e->getMessage(),
                ]);

                $campaign->update(['status' => CampaignStatus::failed]);
            }
        }

        return self::SUCCESS;
    }
}
