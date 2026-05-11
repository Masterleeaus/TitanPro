<?php

namespace Modules\CallingAgent\Tests\Feature;

use Modules\CallingAgent\Contracts\RealtimeVoiceProvider;
use Modules\CallingAgent\Contracts\STTProvider;
use Modules\CallingAgent\Contracts\TTSProvider;
use Modules\CallingAgent\Contracts\TelephonyProvider;
use Modules\CallingAgent\Services\Providers\ElevenLabs\ElevenLabsRealtimeVoiceProvider;
use Modules\CallingAgent\Services\Providers\OpenAI\OpenAIRealtimeProvider;
use Modules\CallingAgent\Services\Providers\ProviderFailoverManager;
use Modules\CallingAgent\Services\Providers\Twilio\UnifiedTwilioProvider;
use Modules\CallingAgent\Services\Providers\VoiceProviderManager;
use Tests\TestCase;

class CallingAgentProviderWiringTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(\Modules\CallingAgent\Providers\ModuleServiceProvider::class);
    }

    public function test_module_provider_registers_telephony_and_voice_drivers(): void
    {
        $manager = $this->app->make(VoiceProviderManager::class);

        $this->assertInstanceOf(UnifiedTwilioProvider::class, $manager->get('twilio'));
        $this->assertInstanceOf(ElevenLabsRealtimeVoiceProvider::class, $manager->get('elevenlabs'));
        $this->assertInstanceOf(OpenAIRealtimeProvider::class, $manager->get('openai'));
    }

    public function test_contract_bindings_resolve_expected_provider_implementations(): void
    {
        $this->assertInstanceOf(UnifiedTwilioProvider::class, $this->app->make(TelephonyProvider::class));
        $this->assertInstanceOf(ElevenLabsRealtimeVoiceProvider::class, $this->app->make(TTSProvider::class));
        $this->assertInstanceOf(OpenAIRealtimeProvider::class, $this->app->make(STTProvider::class));
        $this->assertInstanceOf(ElevenLabsRealtimeVoiceProvider::class, $this->app->make(RealtimeVoiceProvider::class));
    }

    public function test_failover_manager_prefers_healthy_provider_and_plans_layers(): void
    {
        $manager = $this->app->make(ProviderFailoverManager::class);

        $this->assertSame(
            'openai',
            $manager->choose(
                ['elevenlabs', 'openai', 'twilio'],
                [
                    'elevenlabs' => ['ok' => false],
                    'openai' => ['ok' => true, 'quota_available' => true],
                    'twilio' => ['ok' => true, 'quota_available' => true],
                ],
                'elevenlabs',
            ),
        );

        $this->assertSame(
            [
                'tts' => 'twilio',
                'realtime' => 'openai',
            ],
            $manager->plan(
                [
                    'tts' => ['elevenlabs', 'twilio'],
                    'realtime' => ['openai', 'elevenlabs'],
                ],
                [
                    'tts' => [
                        'elevenlabs' => ['ok' => false],
                        'twilio' => ['ok' => true],
                    ],
                    'realtime' => [
                        'openai' => ['ok' => true],
                    ],
                ],
            ),
        );
    }
}
