<?php

namespace App\Extensions\MarketingBot\System\Services\Whatsapp;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Models\Whatsapp\WhatsappChannel;
use App\Extensions\MarketingBot\System\Services\TitanTalk\Channels\ChannelMessageRecorder;
use App\Extensions\MarketingBot\System\Services\TitanTalk\Channels\WhatsappPayloadNormalizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Extensions\MarketingBot\System\Support\TitanTalkConfig;

class WhatsappWebhookService
{
    public function __construct(
        protected WhatsappPayloadNormalizer $normalizer,
        protected ChannelMessageRecorder $recorder,
    ) {}

    public function handle(Request $request, WhatsappChannel $whatsappChannel): void
    {
        $normalized = $this->normalizer->normalize($request->all());
        $marketingConversation = $this->updateOrCreateMarketingConversation($normalized, $whatsappChannel);

        if (($normalized['message_type'] ?? 'text') === 'text' && ! empty($normalized['message'])) {
            $this->recorder->recordInbound($marketingConversation, $normalized);

            app(WhatsappReplyService::class)
                ->setWhatsappChannel($whatsappChannel)
                ->sendReply($request, $marketingConversation);
        }
    }

    /**
     * @param array<string,mixed> $normalized
     */
    public function updateOrCreateMarketingConversation(array $normalized, WhatsappChannel $whatsappChannel): Model|Builder
    {
        return MarketingConversation::query()
            ->firstOrCreate([
                'user_id' => $whatsappChannel->getAttribute('user_id'),
                'type' => 'whatsapp',
                'session_id' => (string) ($normalized['session_id'] ?? ''),
                'whatsapp_channel_id' => $whatsappChannel->getKey(),
            ], [
                'conversation_name' => (string) ($normalized['conversation_name'] ?? 'Number'),
                'role_pack' => TitanTalkConfig::defaultRolePack(),
                'state' => 'new',
                'customer_payload' => $normalized['customer_payload'] ?? [],
            ]);
    }
}
