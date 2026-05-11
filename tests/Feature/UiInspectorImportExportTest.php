<?php

use App\Models\Organization;
use App\Models\UiOverride;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Carbon;

beforeEach(function () {
    (new RolesAndPermissionsSeeder)->run();
});

function uiInspectorOwner(string $orgName = 'Acme Inc'): array
{
    $org = Organization::factory()->create(['name' => $orgName]);
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('owner');

    return [$org, $user];
}

test('ui inspector overrides can round-trip via export and import', function () {
    Carbon::setTestNow('2026-05-11 09:00:00');

    [$org, $user] = uiInspectorOwner('Acme Labs');

    UiOverride::upsertForComponent('component-a', ['padding' => '1rem', 'color' => '#ffffff'], $org->id, $user->id);
    UiOverride::upsertForComponent('component-b', ['box-shadow' => '0 4px 12px rgba(0,0,0,.12)'], $org->id, $user->id);

    $otherOrg = Organization::factory()->create();
    UiOverride::upsertForComponent('other-component', ['padding' => '2rem'], $otherOrg->id, null);

    $response = $this->actingAs($user)->get('/titan/ui-inspector/export');

    $response->assertOk();
    expect((string) $response->headers->get('content-disposition'))
        ->toContain('attachment;')
        ->toContain('ui-overrides-acme-labs-2026-05-11.json');

    $exported = json_decode($response->streamedContent(), true);
    $expected = [
        'component-a' => ['padding' => '1rem', 'color' => '#ffffff'],
        'component-b' => ['box-shadow' => '0 4px 12px rgba(0,0,0,.12)'],
    ];
    ksort($expected);

    expect($exported)->toBe($expected);

    $this->actingAs($user)->delete('/titan/ui-inspector/overrides')->assertOk();
    expect(UiOverride::allForOrg($org->id))->toBe([]);

    $this->actingAs($user)
        ->postJson('/titan/ui-inspector/import', $exported)
        ->assertOk()
        ->assertJson(['imported' => 2]);

    expect(UiOverride::allForOrg($org->id))->toBe($expected)
        ->and(UiOverride::allForOrg($otherOrg->id))->toBe(['other-component' => ['padding' => '2rem']]);

    Carbon::setTestNow();
});

test('ui inspector import rejects unknown property keys', function () {
    [, $user] = uiInspectorOwner();

    $this->actingAs($user)
        ->postJson('/titan/ui-inspector/import', [
            'component-a' => ['unknown-property' => '1rem'],
        ])
        ->assertStatus(422);
});

test('ui inspector import rejects invalid css values', function () {
    [, $user] = uiInspectorOwner();

    $this->actingAs($user)
        ->postJson('/titan/ui-inspector/import', [
            'component-a' => ['background-color' => 'url(javascript:alert(1))'],
        ])
        ->assertStatus(422);
});
