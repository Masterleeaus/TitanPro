<?php

test('calling_agent_voice_webhook_rejects_tampered_twilio_signature', function () {
    config([
        'calling-agent.skip_twilio_validation' => false,
        'services.twilio.token' => 'security-test-token',
    ]);

    $response = $this->post('/calling-agent/webhooks/twilio/voice/incoming', [
        'CallSid' => 'CA'.str_repeat('9', 32),
        'From' => '+15005550006',
        'To' => '+15005550001',
    ], [
        'X-Twilio-Signature' => 'tampered-signature',
    ]);

    $response->assertStatus(403);
});

test('calling_agent_voice_webhook_rejects_missing_twilio_signature_header', function () {
    config([
        'calling-agent.skip_twilio_validation' => false,
        'services.twilio.token' => 'security-test-token',
    ]);

    $response = $this->post('/calling-agent/webhooks/twilio/voice/incoming', [
        'CallSid' => 'CA'.str_repeat('8', 32),
        'From' => '+15005550006',
        'To' => '+15005550001',
    ]);

    $response->assertStatus(403);
});
