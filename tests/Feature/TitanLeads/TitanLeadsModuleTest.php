<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Modules\TitanLeads\Models\MarketingCampaign;
use Modules\TitanLeads\Models\MarketingConversation;
use Modules\TitanLeads\Models\SmsChannel;
use Modules\TitanLeads\Models\Whatsapp\Segment;
use Modules\TitanLeads\Parsers\InboundMessageParser;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

// ---------------------------------------------------------------------------
// Helper
// ---------------------------------------------------------------------------

function titanLeadsUser(string $role): array
{
    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);
    $user->assignRole($role);

    return [$user, $organization];
}

// ---------------------------------------------------------------------------
// Filament panel routing
// ---------------------------------------------------------------------------

dataset('titanleads_resources', [
    'leads inbox'     => [MarketingConversation::class, '/titannexus/leads-inbox'],
    'leads campaigns' => [MarketingCampaign::class, '/titannexus/leads-campaigns'],
    'leads segments'  => [Segment::class, '/titannexus/leads-segments'],
    'leads channels'  => [SmsChannel::class, '/titannexus/leads-channels'],
]);

dataset('titanleads_roles', ['owner', 'admin']);

test('owner and admin can list TitanLeads resources', function (string $role, string $modelClass, string $basePath) {
    [$user] = titanLeadsUser($role);

    $this->actingAs($user)->get($basePath)->assertOk();
})->with('titanleads_roles')->with('titanleads_resources');

test('owner and admin can access create page for TitanLeads resources', function (string $role, string $modelClass, string $basePath) {
    [$user] = titanLeadsUser($role);

    $this->actingAs($user)->get("{$basePath}/create")->assertOk();
})->with('titanleads_roles')->with('titanleads_resources');

test('super admin is denied access to TitanLeads resources', function (string $modelClass, string $basePath) {
    [$user] = titanLeadsUser('super_admin');

    $this->actingAs($user)->get($basePath)->assertForbidden();
})->with('titanleads_resources');

// ---------------------------------------------------------------------------
// InboundMessageParser
// ---------------------------------------------------------------------------

test('InboundMessageParser normalises a WhatsApp Twilio payload', function () {
    $parser = new InboundMessageParser();

    $result = $parser->parse('whatsapp', [
        'From' => 'whatsapp:+61412345678',
        'Body' => 'Hello from WhatsApp',
    ]);

    expect($result['channel'])->toBe('whatsapp')
        ->and($result['from'])->toBe('whatsapp:+61412345678')
        ->and($result['body'])->toBe('Hello from WhatsApp')
        ->and($result['raw'])->toBeArray();
});

test('InboundMessageParser normalises an SMS Twilio payload', function () {
    $parser = new InboundMessageParser();

    $result = $parser->parse('sms', [
        'From' => '+61412345678',
        'Body' => 'Hello from SMS',
    ]);

    expect($result['channel'])->toBe('sms')
        ->and($result['from'])->toBe('+61412345678')
        ->and($result['body'])->toBe('Hello from SMS');
});

test('InboundMessageParser normalises a Telegram update payload', function () {
    $parser = new InboundMessageParser();

    $result = $parser->parse('telegram', [
        'message' => [
            'from' => ['id' => 123456789],
            'text' => 'Hello from Telegram',
        ],
    ]);

    expect($result['channel'])->toBe('telegram')
        ->and($result['from'])->toBe('123456789')
        ->and($result['body'])->toBe('Hello from Telegram');
});

test('InboundMessageParser normalises a Messenger webhook payload', function () {
    $parser = new InboundMessageParser();

    $result = $parser->parse('messenger', [
        'entry' => [[
            'messaging' => [[
                'sender'  => ['id' => 'PSID_987'],
                'message' => ['text' => 'Hello from Messenger'],
            ]],
        ]],
    ]);

    expect($result['channel'])->toBe('messenger')
        ->and($result['from'])->toBe('PSID_987')
        ->and($result['body'])->toBe('Hello from Messenger');
});

test('InboundMessageParser normalises a Voice/Twilio transcription payload', function () {
    $parser = new InboundMessageParser();

    $result = $parser->parse('voice', [
        'From'               => '+61412345678',
        'TranscriptionText'  => 'Please call me back',
    ]);

    expect($result['channel'])->toBe('voice')
        ->and($result['from'])->toBe('+61412345678')
        ->and($result['body'])->toBe('Please call me back');
});

test('InboundMessageParser returns safe defaults for an unknown channel', function () {
    $parser = new InboundMessageParser();

    $result = $parser->parse('fax', ['data' => 'whatever']);

    expect($result['channel'])->toBe('fax')
        ->and($result['from'])->toBe('')
        ->and($result['body'])->toBe('');
});

// ---------------------------------------------------------------------------
// Invoice follow-up model (unit-level smoke test)
// ---------------------------------------------------------------------------

test('InvoiceFollowup model has expected fillable columns', function () {
    $model = new \Modules\TitanLeads\Models\InvoiceFollowup();

    $fillable = $model->getFillable();

    expect($fillable)->toContain('invoice_ref')
        ->and($fillable)->toContain('customer_phone')
        ->and($fillable)->toContain('due_date')
        ->and($fillable)->toContain('status');
});

// ---------------------------------------------------------------------------
// Segment filtering (model scope smoke test)
// ---------------------------------------------------------------------------

test('Segment model uses ext_segments table', function () {
    $model = new Segment();

    expect($model->getTable())->toBe('ext_segments');
});

// ---------------------------------------------------------------------------
// Campaign model
// ---------------------------------------------------------------------------

test('MarketingCampaign model uses ext_marketing_campaigns table', function () {
    $model = new MarketingCampaign();

    expect($model->getTable())->toBe('ext_marketing_campaigns');
});

test('MarketingCampaign model casts contacts and segments as array', function () {
    $model = new MarketingCampaign();
    $casts = $model->getCasts();

    expect($casts)->toHaveKey('contacts')
        ->and($casts['contacts'])->toBe('array')
        ->and($casts)->toHaveKey('segments')
        ->and($casts['segments'])->toBe('array');
});
