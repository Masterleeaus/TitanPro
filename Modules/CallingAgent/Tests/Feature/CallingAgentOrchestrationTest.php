<?php

namespace Modules\CallingAgent\Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(\Modules\CallingAgent\Providers\ModuleServiceProvider::class);
        TenantContext::clear();
        $this->setUpCallingAgentTables();
    }

    protected function tearDown(): void
    {
        $this->tearDownCallingAgentTables();
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

    private function setUpCallingAgentTables(): void
    {
        foreach ([
            'calling_agent_missed_call_recovery_tasks',
            'calling_agent_call_outcomes',
            'calling_agent_webhook_idempotency',
            'calling_agent_messages',
            'calling_agent_caller_profiles',
            'calling_agent_transcripts',
            'calling_agent_active_calls',
            'calling_agent_calls',
            'calling_agent_phone_numbers',
            'calling_agents',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::create('calling_agents', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->text('first_message')->nullable();
            $table->longText('instructions')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('calling_agent_phone_numbers', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('calling_agent_id')->nullable();
            $table->string('number')->unique();
            $table->timestamps();
        });

        Schema::create('calling_agent_calls', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('calling_agent_id')->nullable();
            $table->string('provider')->default('twilio');
            $table->string('call_sid')->nullable()->index();
            $table->string('direction')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('status')->default('queued');
            $table->integer('duration')->default(0);
            $table->string('recording_url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });

        Schema::create('calling_agent_active_calls', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('calling_agent_call_id')->nullable();
            $table->string('call_sid')->unique();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('state')->default('ringing');
            $table->json('context')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });

        Schema::create('calling_agent_transcripts', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('calling_agent_call_id');
            $table->string('role')->default('user');
            $table->longText('text');
            $table->string('source')->nullable();
            $table->timestamps();
        });

        Schema::create('calling_agent_caller_profiles', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->json('tags')->nullable();
            $table->json('preferences')->nullable();
            $table->json('last_outcome')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('last_call_at')->nullable();
            $table->unsignedInteger('call_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('calling_agent_messages', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('calling_agent_id')->nullable();
            $table->string('provider')->default('twilio');
            $table->string('channel')->default('sms');
            $table->string('message_sid')->nullable()->index();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->longText('body')->nullable();
            $table->string('direction')->nullable();
            $table->string('status')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('calling_agent_webhook_idempotency', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('event_id')->unique();
            $table->string('source')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->string('payload_hash')->nullable();
            $table->timestamps();
        });

        Schema::create('calling_agent_call_outcomes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('call_sid')->nullable()->index();
            $table->string('intent')->nullable();
            $table->string('urgency')->nullable();
            $table->string('lead_quality')->nullable();
            $table->boolean('handoff_required')->default(false);
            $table->boolean('booking_requested')->default(false);
            $table->string('sentiment')->nullable();
            $table->json('entities')->nullable();
            $table->json('next_actions')->nullable();
            $table->text('summary')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
        });

        Schema::create('calling_agent_missed_call_recovery_tasks', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('calling_agent_call_id')->nullable();
            $table->string('call_sid')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('channel')->default('sms');
            $table->string('status')->default('pending');
            $table->integer('attempts')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('last_attempted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    private function tearDownCallingAgentTables(): void
    {
        foreach ([
            'calling_agent_missed_call_recovery_tasks',
            'calling_agent_call_outcomes',
            'calling_agent_webhook_idempotency',
            'calling_agent_messages',
            'calling_agent_caller_profiles',
            'calling_agent_transcripts',
            'calling_agent_active_calls',
            'calling_agent_calls',
            'calling_agent_phone_numbers',
            'calling_agents',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
}
