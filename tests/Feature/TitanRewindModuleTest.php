<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\DB;
use Modules\TitanRewind\Filament\Resources\RewindCaseResource;
use Modules\TitanRewind\Models\RewindAction;
use Modules\TitanRewind\Models\RewindCase;
use Modules\TitanRewind\Models\RewindEvent;
use Modules\TitanRewind\Models\RewindFix;
use Modules\TitanRewind\Services\RewindAuditService;
use Modules\TitanRewind\Services\RewindCaseService;
use Modules\TitanRewind\Services\RewindFixService;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

function titanRewindUser(string $role): array
{
    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);
    $user->assignRole($role);

    DB::table('titan_rewind_actions')->delete();
    DB::table('titan_rewind_fixes')->delete();
    DB::table('titan_rewind_events')->delete();
    DB::table('titan_rewind_cases')->delete();

    return [$user, $organization];
}

test('tracked user mutations create rewind case and events', function () {
    [$owner, $organization] = titanRewindUser('owner');

    $this->actingAs($owner);

    $target = User::factory()->create([
        'organization_id' => $organization->id,
        'name' => 'Initial Name',
    ]);

    $target->forceFill(['name' => 'Updated Name'])->save();

    $case = RewindCase::query()
        ->where('company_id', $organization->id)
        ->where('case_key', User::class.':'.$target->id)
        ->first();

    expect($case)->not->toBeNull();
    expect(RewindEvent::query()->where('case_id', $case->id)->pluck('event_type')->all())
        ->toContain('created', 'updated');
});

test('rewind case lifecycle supports event attachment ai suggestion fix apply and close', function () {
    [$owner, $organization] = titanRewindUser('owner');

    $caseService = app(RewindCaseService::class);
    $auditService = app(RewindAuditService::class);
    $fixService = app(RewindFixService::class);

    $historicalCase = $caseService->openCase([
        'company_id' => $organization->id,
        'case_key' => 'historical-user-incident',
        'title' => 'Historical user incident',
        'entity_type' => User::class,
        'entity_id' => '100',
    ]);

    $auditService->appendEvent([
        'company_id' => $organization->id,
        'case' => $historicalCase,
        'event_type' => 'updated',
        'entity_type' => User::class,
        'entity_id' => '100',
        'actor_type' => 'user',
        'actor_id' => $owner->id,
        'payload_json' => ['changes' => ['name' => 'Historical']],
    ]);

    $case = $caseService->openCase([
        'company_id' => $organization->id,
        'case_key' => 'active-user-incident',
        'title' => 'Active user incident',
        'entity_type' => User::class,
        'entity_id' => '200',
    ]);

    $auditService->appendEvent([
        'company_id' => $organization->id,
        'case' => $case,
        'event_type' => 'updated',
        'entity_type' => User::class,
        'entity_id' => '200',
        'actor_type' => 'user',
        'actor_id' => $owner->id,
        'payload_json' => ['changes' => ['name' => 'Current']],
    ]);

    $aiSuggestion = RewindFix::query()
        ->where('case_id', $case->id)
        ->where('proposed_by_type', 'ai')
        ->first();

    expect($aiSuggestion)->not->toBeNull();

    $manualFix = $fixService->proposeFix($case, [
        'fix_type' => 'metadata_update',
        'target_table' => 'titan_rewind_cases',
        'target_id' => $case->id,
        'meta_key' => 'resolution_note',
        'meta_value' => 'Corrected by owner',
    ], [
        'type' => 'user',
        'id' => $owner->id,
    ], requiresConfirmation: false);

    $appliedFix = $fixService->applyFix($manualFix, [
        'type' => 'user',
        'id' => $owner->id,
    ]);

    $closedCase = $caseService->closeCase($case, [
        'type' => 'user',
        'id' => $owner->id,
    ]);

    expect($appliedFix->status)->toBe('applied');
    expect(RewindAction::query()->where('case_id', $case->id)->where('success', true)->exists())->toBeTrue();
    expect($closedCase->status)->toBe('closed');
    expect($closedCase->meta_json)->toMatchArray(['resolution_note' => 'Corrected by owner']);
});

test('TitanRewind filament resource access is owner-only at the resource layer', function () {
    [$owner] = titanRewindUser('owner');
    auth()->login($owner);

    expect(RewindCaseResource::canViewAny())->toBeTrue();

    auth()->logout();

    [$superAdmin] = titanRewindUser('super_admin');
    auth()->login($superAdmin);

    expect(RewindCaseResource::canViewAny())->toBeFalse();
});
