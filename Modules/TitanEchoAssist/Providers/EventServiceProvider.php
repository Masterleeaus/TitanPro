<?php

namespace Modules\TitanEchoAssist\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\TitanEchoAssist\Automation\Handlers\EscalateToAgentHandler;
use Modules\TitanEchoAssist\Automation\Handlers\SendSuggestedPromptsHandler;
use Modules\TitanEchoAssist\Automation\Handlers\SendWelcomeBannerHandler;
use Modules\TitanEchoAssist\Events\ConversationEscalated;
use Modules\TitanEchoAssist\Events\ConversationStarted;
use Modules\TitanEchoAssist\Events\IntentDetected;
use Modules\TitanEchoAssist\Events\MessageReceived;
use Modules\TitanEchoAssist\Events\VoiceSessionDurationRecorded;
use Modules\TitanEchoAssist\Events\VoiceSessionStarted;
use Modules\TitanEchoAssist\Listeners\RecordVoiceSessionBillingListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ConversationStarted::class => [
            SendWelcomeBannerHandler::class,
            SendSuggestedPromptsHandler::class,
        ],
        ConversationEscalated::class => [
            EscalateToAgentHandler::class,
        ],
        IntentDetected::class => [],
        VoiceSessionStarted::class => [],
        VoiceSessionDurationRecorded::class => [
            RecordVoiceSessionBillingListener::class,
        ],
        MessageReceived::class => [],
    ];
}
