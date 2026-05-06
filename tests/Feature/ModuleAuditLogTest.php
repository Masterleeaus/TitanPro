<?php

use App\Models\TitanModuleAuditLog;
use App\Models\User;
use App\Services\ModuleAuditLogger;
use Database\Seeders\RolesAndPermissionsSeeder;

/**
 * Verifies that the ModuleAuditLogger service writes correct records and that
 * the TitanModuleAuditLog model enforces the append-only contract.
 */
beforeEach(function () {
    (new RolesAndPermissionsSeeder)->run();
});

// ── logSync ───────────────────────────────────────────────────────────────────

test('logSync writes a sync record with the authenticated actor', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    /** @var ModuleAuditLogger $logger */
    $logger = app(ModuleAuditLogger::class);
    $logger->logSync('TitanCore');

    $this->assertDatabaseHas('titan_module_audit_log', [
        'actor'   => (string) $user->id,
        'action'  => 'sync',
        'module'  => 'TitanCore',
        'outcome' => 'success',
    ]);
});

test('logSync uses "system" actor when not authenticated', function () {
    /** @var ModuleAuditLogger $logger */
    $logger = app(ModuleAuditLogger::class);
    $logger->logSync('TitanCore', 'failure');

    $this->assertDatabaseHas('titan_module_audit_log', [
        'actor'   => 'system',
        'action'  => 'sync',
        'module'  => 'TitanCore',
        'outcome' => 'failure',
    ]);
});

// ── logEnable ─────────────────────────────────────────────────────────────────

test('logEnable writes an enable record', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    app(ModuleAuditLogger::class)->logEnable('Accountings');

    $this->assertDatabaseHas('titan_module_audit_log', [
        'actor'  => (string) $user->id,
        'action' => 'enable',
        'module' => 'Accountings',
    ]);
});

// ── logDisable ────────────────────────────────────────────────────────────────

test('logDisable writes a disable record', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    app(ModuleAuditLogger::class)->logDisable('TitanNexus');

    $this->assertDatabaseHas('titan_module_audit_log', [
        'actor'  => (string) $user->id,
        'action' => 'disable',
        'module' => 'TitanNexus',
    ]);
});

// ── Context payload ───────────────────────────────────────────────────────────

test('audit record stores optional context as JSON', function () {
    app(ModuleAuditLogger::class)->logSync('TitanZero', 'success', ['version' => '4.0.0']);

    $record = TitanModuleAuditLog::first();

    expect($record->context)->toMatchArray(['version' => '4.0.0']);
});

// ── Append-only enforcement ───────────────────────────────────────────────────

test('TitanModuleAuditLog model throws when attempting to update an existing record', function () {
    TitanModuleAuditLog::create([
        'actor'   => 'system',
        'action'  => 'sync',
        'module'  => 'TitanCore',
        'outcome' => 'success',
    ]);

    $record = TitanModuleAuditLog::first();
    $record->outcome = 'failure';

    expect(fn () => $record->save())->toThrow(\LogicException::class);
});

test('TitanModuleAuditLog model throws when attempting to delete a record', function () {
    TitanModuleAuditLog::create([
        'actor'   => 'system',
        'action'  => 'sync',
        'module'  => 'TitanCore',
        'outcome' => 'success',
    ]);

    $record = TitanModuleAuditLog::first();

    expect(fn () => $record->delete())->toThrow(\LogicException::class);
});

// ── Admin UI ──────────────────────────────────────────────────────────────────

test('audit log index is viewable by super_admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    TitanModuleAuditLog::create([
        'actor'   => 'system',
        'action'  => 'sync',
        'module'  => 'TitanCore',
        'outcome' => 'success',
    ]);

    $this->actingAs($user)
        ->get('/platform/modules/audit-log')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Platform/ModuleAuditLog')
            ->has('entries.data', 1)
        );
});
