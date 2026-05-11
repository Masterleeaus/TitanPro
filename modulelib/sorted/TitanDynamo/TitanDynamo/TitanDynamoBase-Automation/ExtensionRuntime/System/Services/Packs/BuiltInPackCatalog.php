<?php
namespace App\Extensions\TitanPulse\System\Services\Packs;
class BuiltInPackCatalog {
    public function all(): array {
        return [
            'CleaningOpsPack' => [
                'name' => 'CleaningOpsPack',
                'description' => 'Operational work rules for scheduling, dispatch, proof and invoicing.',
                'channels' => ['command_feed','hive_feed'],
            ],
            'QualityRetentionPack' => [
                'name' => 'QualityRetentionPack',
                'description' => 'Quality control, retention and upsell rules.',
                'channels' => ['command_feed'],
            ],
            'MarketingPack' => [
                'name' => 'MarketingPack',
                'description' => 'Lead, inbox, content and campaign automations.',
                'channels' => ['command_feed','studio_feed'],
            ],
            'FinancePack' => [
                'name' => 'FinancePack',
                'description' => 'Invoice, overdue, payment and loyalty automations.',
                'channels' => ['command_feed'],
            ],
            'TrustPack' => [
                'name' => 'TrustPack',
                'description' => 'Complaints, reviews, supervisor and recovery workflows.',
                'channels' => ['command_feed'],
            ],
        ];
    }
}
