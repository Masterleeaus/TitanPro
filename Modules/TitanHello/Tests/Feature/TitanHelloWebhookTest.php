<?php

namespace Modules\TitanHello\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TitanHello\Services\Calls\OutboundCallService;
use Modules\TitanHello\Services\Providers\Twilio\TwilioProvider;
use Modules\TitanHello\Services\Routing\InboundRoutingService;
use Tests\TestCase;

class TitanHelloWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_inbound_webhook_creates_call_record(): void
    {
        $provider = $this->createMock(TwilioProvider::class);
        $provider->method('validateSignature')->willReturn(true);
        $provider->method('mapInbound')->willReturn([
            'company_id' => 44,
            'direction' => 'inbound',
            'provider' => 'twilio',
            'provider_call_sid' => 'CAHELLOINBOUND001',
            'from_number' => '+15550000001',
            'to_number' => '+15550000002',
            'status' => 'ringing',
        ]);
        $this->app->instance(TwilioProvider::class, $provider);

        $routing = $this->createMock(InboundRoutingService::class);
        $routing->method('resolveCompanyIdByToNumber')->willReturn(44);
        $routing->method('buildInboundResponse')->willReturn('<Response><Say>Hello</Say></Response>');
        $this->app->instance(InboundRoutingService::class, $routing);

        $response = $this->post('/titanhello/webhooks/voice/inbound', [
            'CallSid' => 'CAHELLOINBOUND001',
            'From' => '+15550000001',
            'To' => '+15550000002',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('titanhello_calls', [
            'provider_call_sid' => 'CAHELLOINBOUND001',
            'status' => 'ringing',
            'direction' => 'inbound',
            'company_id' => 44,
        ]);
    }

    public function test_outbound_service_dispatches_and_persists_call(): void
    {
        $provider = $this->createMock(TwilioProvider::class);
        $provider->method('createOutboundCall')->willReturn('CAHELLOOUTBOUND001');
        $this->app->instance(TwilioProvider::class, $provider);

        $call = app(OutboundCallService::class)->dialNumber(
            52,
            '+15550000003',
            '+15550000004',
            null,
            ['source' => 'feature_test']
        );

        $this->assertSame('CAHELLOOUTBOUND001', $call->provider_call_sid);
        $this->assertSame('dialing', $call->status);

        $this->assertDatabaseHas('titanhello_calls', [
            'id' => $call->id,
            'company_id' => 52,
            'direction' => 'outbound',
            'provider_call_sid' => 'CAHELLOOUTBOUND001',
        ]);
    }
}
