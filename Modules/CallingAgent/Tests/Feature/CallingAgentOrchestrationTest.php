<?php

namespace Modules\CallingAgent\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\CallingAgent\AI\Agents\ReceptionistAgent;
use Modules\CallingAgent\Models\CallingAgent;
use Modules\CallingAgent\Models\CallingAgentCall;
use Modules\CallingAgent\Models\CallingAgentPhoneNumber;
use Modules\CallingAgent\Models\CallingAgentTranscript;
use Modules\CallingAgent\Services\ReceptionistOrchestrator;
use Modules\CallingAgent\Support\TenantContext;
use Tests\TestCase;

class CallingAgentOrchestrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        TenantContext::clear();
    }

    protected function tearDown(): void
    {
        TenantContext::clear();

        parent::tearDown();
    }

    public function test_start_inbound_resolves_tenant_and_agent_from_number(): void
    {
        $agent = CallingAgent::create([
            'tenant_id' => 101,
            'name' => 'Front Desk',
            'phone_number' => '+15005550001',
        ]);

        CallingAgentPhoneNumber::create([
            'tenant_id' => 101,
            'calling_agent_id' => $agent->id,
            'number' => '+15005550001',
        ]);

        $call = $this->app->make(ReceptionistOrchestrator::class)->startInbound([
            'CallSid' => 'CA'.str_repeat('1', 32),
            'From' => '+15005550006',
            'To' => '+15005550001',
            'CallStatus' => 'ringing',
        ]);

        $this->assertSame(101, $call->tenant_id);
        $this->assertSame($agent->id, $call->calling_agent_id);
        $this->assertDatabaseHas('calling_agent_calls', [
            'call_sid' => 'CA'.str_repeat('1', 32),
            'tenant_id' => 101,
            'calling_agent_id' => $agent->id,
        ]);
    }

    public function test_complete_persists_tenant_scoped_outcomes_and_recovery_tasks(): void
    {
        $call = CallingAgentCall::create([
            'tenant_id' => 202,
            'provider' => 'twilio',
            'call_sid' => 'CA'.str_repeat('2', 32),
            'direction' => 'inbound',
            'from' => '+15005550007',
            'to' => '+15005550001',
            'status' => 'in-progress',
            'started_at' => now()->subMinute(),
        ]);

        CallingAgentTranscript::create([
            'calling_agent_call_id' => $call->id,
            'role' => 'user',
            'text' => 'I need to book an urgent appointment today.',
            'source' => 'twilio-speech',
        ]);

        CallingAgentTranscript::create([
            'calling_agent_call_id' => $call->id,
            'role' => 'assistant',
            'text' => 'I can help with that and escalate if needed.',
            'source' => 'ai',
        ]);

        $this->app->make(ReceptionistOrchestrator::class)->complete($call->call_sid, [
            'CallSid' => $call->call_sid,
            'CallStatus' => 'no-answer',
            'CallDuration' => '30',
        ]);

        $this->assertDatabaseHas('calling_agent_calls', [
            'id' => $call->id,
            'tenant_id' => 202,
            'status' => 'no-answer',
            'duration' => 30,
        ]);

        $this->assertDatabaseHas('calling_agent_call_outcomes', [
            'call_sid' => $call->call_sid,
            'tenant_id' => 202,
            'intent' => 'booking',
        ]);

        $this->assertDatabaseHas('calling_agent_missed_call_recovery_tasks', [
            'call_sid' => $call->call_sid,
            'tenant_id' => 202,
            'status' => 'pending',
        ]);
    }

    public function test_duplicate_detection_is_tenant_scoped(): void
    {
        $orchestrator = $this->app->make(ReceptionistOrchestrator::class);

        TenantContext::setTenantId(10);
        $this->assertFalse($orchestrator->isDuplicate('event-123'));
        $this->assertTrue($orchestrator->isDuplicate('event-123'));

        TenantContext::setTenantId(11);
        $this->assertFalse($orchestrator->isDuplicate('event-123'));

        $this->assertDatabaseHas('calling_agent_webhook_idempotency', [
            'tenant_id' => 10,
            'event_id' => '10:twilio:event-123',
        ]);

        $this->assertDatabaseHas('calling_agent_webhook_idempotency', [
            'tenant_id' => 11,
            'event_id' => '11:twilio:event-123',
        ]);
    }

    public function test_messaging_webhook_persists_messages_for_resolved_tenant_profile(): void
    {
        $agent = CallingAgent::create([
            'tenant_id' => 303,
            'name' => 'Omni Desk',
            'phone_number' => '+15005550001',
        ]);

        CallingAgentPhoneNumber::create([
            'tenant_id' => 303,
            'calling_agent_id' => $agent->id,
            'number' => '+15005550001',
        ]);

        \Modules\CallingAgent\Models\CallingAgentCallerProfile::create([
            'tenant_id' => 303,
            'phone' => '+15005550008',
            'name' => 'Tenant Three Caller',
        ]);

        \Modules\CallingAgent\Models\CallingAgentCallerProfile::create([
            'tenant_id' => 404,
            'phone' => '+15005550008',
            'name' => 'Wrong Tenant Caller',
        ]);

        $agentMock = $this->createMock(ReceptionistAgent::class);
        $agentMock->expects($this->once())
            ->method('respond')
            ->with(
                'Need help with my booking',
                $this->callback(function (array $context): bool {
                    return ($context['caller_profile']['name'] ?? null) === 'Tenant Three Caller';
                }),
            )
            ->willReturn('We can help with your booking.');
        $this->app->instance(ReceptionistAgent::class, $agentMock);

        $twilioMock = $this->createMock(\Modules\CallingAgent\Services\TwilioChannelService::class);
        $twilioMock->expects($this->once())
            ->method('sendSms')
            ->with('+15005550008', 'We can help with your booking.', '+15005550001')
            ->willReturn(['sid' => 'SM123']);
        $this->app->instance(\Modules\CallingAgent\Services\TwilioChannelService::class, $twilioMock);

        $response = $this->withoutMiddleware()->post('/calling-agent/webhooks/twilio/message/incoming', [
            'MessageSid' => 'SM'.str_repeat('3', 16),
            'From' => '+15005550008',
            'To' => '+15005550001',
            'Body' => 'Need help with my booking',
        ]);

        $response->assertNoContent();

        $this->assertDatabaseHas('calling_agent_messages', [
            'tenant_id' => 303,
            'calling_agent_id' => $agent->id,
            'message_sid' => 'SM'.str_repeat('3', 16),
            'direction' => 'inbound',
        ]);

        $this->assertDatabaseHas('calling_agent_messages', [
            'tenant_id' => 303,
            'calling_agent_id' => $agent->id,
            'direction' => 'outbound',
            'body' => 'We can help with your booking.',
        ]);
    }
}
