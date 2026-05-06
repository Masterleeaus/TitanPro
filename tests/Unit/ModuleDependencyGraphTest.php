<?php

use Modules\TitanCore\Support\ModuleDependencyGraph;
use Nwidart\Modules\Contracts\RepositoryInterface;

// Helper to create a graph pre-loaded with synthetic nodes (no real modules needed)
function makeGraph(array $nodes): ModuleDependencyGraph
{
    $repo = Mockery::mock(RepositoryInterface::class);
    $graph = new ModuleDependencyGraph($repo);
    $graph->setNodes($nodes);

    return $graph;
}

afterEach(fn () => Mockery::close());

// ─── Load-order resolver ──────────────────────────────────────────────────────

test('resolves load order respecting simple requires', function () {
    $graph = makeGraph([
        'A' => ['requires' => ['B'], 'enabled' => true],
        'B' => ['requires' => [], 'enabled' => true],
        'C' => ['requires' => ['A', 'B'], 'enabled' => true],
    ]);

    $order = $graph->resolveLoadOrder();

    expect($order)->toContain('A', 'B', 'C');
    // B must appear before A; A and B before C
    expect(array_search('B', $order))->toBeLessThan(array_search('A', $order));
    expect(array_search('A', $order))->toBeLessThan(array_search('C', $order));
    expect(array_search('B', $order))->toBeLessThan(array_search('C', $order));
});

test('resolves load order for independent modules deterministically', function () {
    $graph = makeGraph([
        'Z' => ['requires' => [], 'enabled' => true],
        'A' => ['requires' => [], 'enabled' => true],
        'M' => ['requires' => [], 'enabled' => true],
    ]);

    $order = $graph->resolveLoadOrder();

    expect($order)->toBe(['A', 'M', 'Z']); // alphabetic = deterministic
});

test('appends cyclic modules at end of load order instead of dropping them', function () {
    $graph = makeGraph([
        'A' => ['requires' => ['B'], 'enabled' => true],
        'B' => ['requires' => ['A'], 'enabled' => true],
        'C' => ['requires' => [], 'enabled' => true],
    ]);

    $order = $graph->resolveLoadOrder();

    // All three modules must appear
    expect($order)->toHaveCount(3);
    expect($order)->toContain('A', 'B', 'C');
    // C has no deps so it should appear before cyclic nodes
    expect(array_search('C', $order))->toBeLessThan(min(array_search('A', $order), array_search('B', $order)));
});

// ─── Circular dependency detection ───────────────────────────────────────────

test('detects a simple two-node cycle', function () {
    $graph = makeGraph([
        'A' => ['requires' => ['B'], 'enabled' => true],
        'B' => ['requires' => ['A'], 'enabled' => true],
    ]);

    $cycles = $graph->detectCycles();

    expect($cycles)->not->toBeEmpty();
    $flat = array_merge(...$cycles);
    expect($flat)->toContain('A', 'B');
});

test('detects a three-node cycle', function () {
    $graph = makeGraph([
        'A' => ['requires' => ['B'], 'enabled' => true],
        'B' => ['requires' => ['C'], 'enabled' => true],
        'C' => ['requires' => ['A'], 'enabled' => true],
    ]);

    $cycles = $graph->detectCycles();

    expect($cycles)->not->toBeEmpty();
    $flat = array_merge(...$cycles);
    expect($flat)->toContain('A', 'B', 'C');
});

test('reports no cycles for a linear dependency chain', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => true],
        'B' => ['requires' => ['A'], 'enabled' => true],
        'C' => ['requires' => ['B'], 'enabled' => true],
    ]);

    expect($graph->detectCycles())->toBeEmpty();
});

test('reports no cycles for a diamond dependency graph', function () {
    $graph = makeGraph([
        'D' => ['requires' => [], 'enabled' => true],
        'B' => ['requires' => ['D'], 'enabled' => true],
        'C' => ['requires' => ['D'], 'enabled' => true],
        'A' => ['requires' => ['B', 'C'], 'enabled' => true],
    ]);

    expect($graph->detectCycles())->toBeEmpty();
});

// ─── Enable-time validation ───────────────────────────────────────────────────

test('validates successfully when all requirements are enabled', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => true, 'version' => '1.0.0'],
        'B' => ['requires' => ['A'], 'enabled' => false, 'version' => '1.0.0'],
    ]);

    $result = $graph->validateEnableModule('B');

    expect($result['errors'])->toBeEmpty();
    expect($result['warnings'])->toBeEmpty();
});

test('blocks enable when a required module is disabled', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => false, 'version' => '1.0.0'],
        'B' => ['requires' => ['A'], 'enabled' => false, 'version' => '1.0.0'],
    ]);

    $result = $graph->validateEnableModule('B');

    expect($result['errors'])->not->toBeEmpty();
    expect($result['errors'][0])->toContain("'A' is disabled");
});

test('blocks enable when a required module is not installed', function () {
    $graph = makeGraph([
        'B' => ['requires' => ['NonExistentModule'], 'enabled' => false],
    ]);

    $result = $graph->validateEnableModule('B');

    expect($result['errors'])->not->toBeEmpty();
    expect($result['errors'][0])->toContain('not installed');
});

test('blocks enable when a conflicting module is enabled', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => true],
        'B' => ['requires' => [], 'conflicts' => ['A'], 'enabled' => false],
    ]);

    $result = $graph->validateEnableModule('B');

    expect($result['errors'])->not->toBeEmpty();
    expect($result['errors'][0])->toContain('conflicts');
});

test('passes when conflicting module is disabled', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => false],
        'B' => ['requires' => [], 'conflicts' => ['A'], 'enabled' => false],
    ]);

    $result = $graph->validateEnableModule('B');

    expect($result['errors'])->toBeEmpty();
});

// ─── Semver version constraint validation ─────────────────────────────────────

test('passes when installed version satisfies caret constraint', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => true, 'version' => '1.5.0'],
        'B' => ['requires' => ['A:^1.0'], 'enabled' => false, 'version' => '2.0.0'],
    ]);

    $result = $graph->validateEnableModule('B');

    expect($result['errors'])->toBeEmpty();
});

test('blocks when installed version violates caret constraint', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => true, 'version' => '2.0.0'],
        'B' => ['requires' => ['A:^1.0'], 'enabled' => false, 'version' => '1.0.0'],
    ]);

    $result = $graph->validateEnableModule('B');

    expect($result['errors'])->not->toBeEmpty();
    expect($result['errors'][0])->toContain('does not satisfy');
});

test('passes when installed version satisfies gte constraint', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => true, 'version' => '3.0.0'],
        'B' => ['requires' => ['A:>=2.0'], 'enabled' => false],
    ]);

    $result = $graph->validateEnableModule('B');

    expect($result['errors'])->toBeEmpty();
});

test('warns when required module has no version for constraint check', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => true, 'version' => '0.0.0'],
        'B' => ['requires' => ['A:^1.0'], 'enabled' => false],
    ]);

    $result = $graph->validateEnableModule('B');

    expect($result['errors'])->toBeEmpty();
    expect($result['warnings'])->not->toBeEmpty();
    expect($result['warnings'][0])->toContain('no declared version');
});

test('ignores composer package requires during module validation', function () {
    $graph = makeGraph([
        'A' => ['requires' => ['vendor/some-package'], 'enabled' => false],
    ]);

    $result = $graph->validateEnableModule('A');

    expect($result['errors'])->toBeEmpty();
});

// ─── Dependency tree ──────────────────────────────────────────────────────────

test('builds a single-level dependency tree', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => true, 'version' => '1.0.0'],
        'B' => ['requires' => ['A'], 'enabled' => true, 'version' => '2.0.0'],
    ]);

    $tree = $graph->getDependencyTree('B');

    expect($tree['name'])->toBe('B');
    expect($tree['children'])->toHaveCount(1);
    expect($tree['children'][0]['name'])->toBe('A');
});

test('marks missing modules in the tree', function () {
    $graph = makeGraph([
        'B' => ['requires' => ['Ghost'], 'enabled' => true],
    ]);

    $tree = $graph->getDependencyTree('B');

    expect($tree['children'][0]['missing'])->toBeTrue();
});

test('marks circular references in the tree', function () {
    $graph = makeGraph([
        'A' => ['requires' => ['B'], 'enabled' => true],
        'B' => ['requires' => ['A'], 'enabled' => true],
    ]);

    $tree = $graph->getDependencyTree('A');

    // The child B should reference back to A which is already in the path
    $child = $tree['children'][0]; // B
    expect($child['name'])->toBe('B');

    // B's child A should be marked circular
    $grandchild = $child['children'][0];
    expect($grandchild['name'])->toBe('A');
    expect($grandchild['circular'] ?? false)->toBeTrue();
});

test('separates composer deps into composer_deps key', function () {
    $graph = makeGraph([
        'A' => ['requires' => ['vendor/package', 'another/lib:^2.0'], 'enabled' => true],
    ]);

    $tree = $graph->getDependencyTree('A');

    expect($tree['children'])->toBeEmpty();
    expect($tree['composer_deps'])->toHaveCount(2);
    expect($tree['composer_deps'][0]['name'])->toBe('vendor/package');
    expect($tree['composer_deps'][1]['constraint'])->toBe('^2.0');
});

// ─── getAllIssues ─────────────────────────────────────────────────────────────

test('getAllIssues returns empty when no issues exist', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => true],
        'B' => ['requires' => ['A'], 'enabled' => true],
    ]);

    expect($graph->getAllIssues())->toBeEmpty();
});

test('getAllIssues captures issues across multiple modules', function () {
    $graph = makeGraph([
        'A' => ['requires' => [], 'enabled' => false],
        'B' => ['requires' => ['A'], 'enabled' => true],
        'C' => ['requires' => ['Missing'], 'enabled' => true],
    ]);

    $issues = $graph->getAllIssues();

    expect($issues)->toHaveKey('B');
    expect($issues)->toHaveKey('C');
});
