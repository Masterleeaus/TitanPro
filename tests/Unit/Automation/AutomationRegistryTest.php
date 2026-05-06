<?php

use App\Platform\Automation\AutomationRegistry;

// ─── Registration ────────────────────────────────────────────────────────────

test('registers an automation and retrieves it by id', function () {
    $registry = new AutomationRegistry;

    $registry->register([
        'id'      => 'crm.deal_won',
        'trigger' => 'crmcore.deal.won',
        'handler' => 'SomeHandlerClass',
    ]);

    $found = $registry->find('crm.deal_won');

    expect($found)->not->toBeNull();
    expect($found['id'])->toBe('crm.deal_won');
    expect($found['trigger'])->toBe('crmcore.deal.won');
    expect($found['handler'])->toBe('SomeHandlerClass');
});

test('ignores registrations without an id', function () {
    $registry = new AutomationRegistry;

    $registry->register(['trigger' => 'some.trigger', 'handler' => 'SomeClass']);

    expect($registry->all())->toBeEmpty();
});

test('registration applies default values', function () {
    $registry = new AutomationRegistry;

    $registry->register(['id' => 'test.auto', 'trigger' => 't', 'handler' => 'H']);

    $found = $registry->find('test.auto');

    expect($found['retries'])->toBe(3);
    expect($found['retry_after'])->toBe(60);
    expect($found['pipeline'])->toBeNull();
    expect($found['schedule'])->toBeNull();
    expect($found['company_id'])->toBeNull();
});

test('later registration overwrites earlier one with same id', function () {
    $registry = new AutomationRegistry;

    $registry->register(['id' => 'dup', 'trigger' => 'first', 'handler' => 'H1']);
    $registry->register(['id' => 'dup', 'trigger' => 'second', 'handler' => 'H2']);

    $found = $registry->find('dup');

    expect($found['trigger'])->toBe('second');
    expect($found['handler'])->toBe('H2');
    expect($registry->all())->toHaveCount(1);
});

// ─── forTrigger ──────────────────────────────────────────────────────────────

test('forTrigger returns all automations matching the trigger key', function () {
    $registry = new AutomationRegistry;

    $registry->register(['id' => 'a1', 'trigger' => 'model.created', 'handler' => 'H1']);
    $registry->register(['id' => 'a2', 'trigger' => 'model.created', 'handler' => 'H2']);
    $registry->register(['id' => 'a3', 'trigger' => 'model.updated', 'handler' => 'H3']);

    $matches = $registry->forTrigger('model.created');

    expect($matches)->toHaveCount(2);
    expect(array_column($matches, 'id'))->toContain('a1', 'a2');
    expect(array_column($matches, 'id'))->not->toContain('a3');
});

test('forTrigger returns empty array when no automation matches', function () {
    $registry = new AutomationRegistry;

    $registry->register(['id' => 'a1', 'trigger' => 'other.trigger', 'handler' => 'H1']);

    expect($registry->forTrigger('non.existent'))->toBeEmpty();
});

// ─── scheduled ───────────────────────────────────────────────────────────────

test('scheduled returns only automations with a schedule cadence', function () {
    $registry = new AutomationRegistry;

    $registry->register(['id' => 's1', 'trigger' => 't1', 'handler' => 'H1', 'schedule' => 'daily']);
    $registry->register(['id' => 's2', 'trigger' => 't2', 'handler' => 'H2']); // no schedule
    $registry->register(['id' => 's3', 'trigger' => 't3', 'handler' => 'H3', 'schedule' => 'hourly']);

    $scheduled = $registry->scheduled();

    expect($scheduled)->toHaveCount(2);
    expect(array_column($scheduled, 'id'))->toContain('s1', 's3');
    expect(array_column($scheduled, 'id'))->not->toContain('s2');
});

// ─── all ─────────────────────────────────────────────────────────────────────

test('all returns every registered automation keyed by id', function () {
    $registry = new AutomationRegistry;

    $registry->register(['id' => 'x', 'trigger' => 't', 'handler' => 'H']);
    $registry->register(['id' => 'y', 'trigger' => 't', 'handler' => 'H']);

    $all = $registry->all();

    expect($all)->toHaveCount(2);
    expect($all)->toHaveKey('x');
    expect($all)->toHaveKey('y');
});
