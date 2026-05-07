<?php

use Modules\TitanCore\Support\ManifestSchemaValidator;
use Modules\TitanCore\Support\ManifestValidationResult;

/**
 * Tests for ManifestSchemaValidator.
 */
describe('ManifestSchemaValidator', function () {

    // ── Schema detection ──────────────────────────────────────────────────────

    test('detectType identifies module.json', function () {
        $v = new ManifestSchemaValidator();
        expect($v->detectType('/path/to/module.json'))->toBe('module.json');
    });

    test('detectType identifies module.manifest.json', function () {
        $v = new ManifestSchemaValidator();
        expect($v->detectType('/path/to/module.manifest.json'))->toBe('module.manifest.json');
    });

    test('detectType identifies ai.manifest.json', function () {
        $v = new ManifestSchemaValidator();
        expect($v->detectType('/some/manifests/ai.manifest.json'))->toBe('ai.manifest');
    });

    test('detectType identifies billing.manifest.json', function () {
        $v = new ManifestSchemaValidator();
        expect($v->detectType('/some/manifests/billing.manifest.json'))->toBe('billing.manifest');
    });

    test('detectType identifies workflow.manifest.json', function () {
        $v = new ManifestSchemaValidator();
        expect($v->detectType('/some/manifests/workflows.manifest.json'))->toBe('workflows.manifest');
    });

    // ── Valid module.json ─────────────────────────────────────────────────────

    test('valid module.json passes validation', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'name'      => 'MyModule',
            'providers' => ['Modules\\MyModule\\Providers\\MyServiceProvider'],
        ], 'module.json');

        expect($result->isValid())->toBeTrue();
        expect($result->status())->toBe(ManifestValidationResult::STATUS_SUCCESS);
    });

    test('module.json missing required name field fails', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'providers' => ['Modules\\X\\Providers\\XServiceProvider'],
        ], 'module.json');

        expect($result->isValid())->toBeFalse();
        expect($result->errors())->not->toBeEmpty();
        expect(implode(' ', $result->errors()))->toContain('"name"');
    });

    test('module.json missing required providers field fails', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'name' => 'MyModule',
        ], 'module.json');

        expect($result->isValid())->toBeFalse();
        expect(implode(' ', $result->errors()))->toContain('"providers"');
    });

    test('module.json with empty providers array fails', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'name'      => 'MyModule',
            'providers' => [],
        ], 'module.json');

        expect($result->isValid())->toBeFalse();
    });

    test('module.json with invalid version format fails', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'name'      => 'MyModule',
            'providers' => ['Modules\\X\\Providers\\XServiceProvider'],
            'version'   => 'not-semver',
        ], 'module.json');

        expect($result->isValid())->toBeFalse();
    });

    test('module.json with valid semver version passes', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'name'      => 'MyModule',
            'providers' => ['Modules\\X\\Providers\\XServiceProvider'],
            'version'   => '1.2.3',
        ], 'module.json');

        expect($result->isValid())->toBeTrue();
    });

    // ── Schema version handling ───────────────────────────────────────────────

    test('manifest with supported schema_version passes', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'schema_version' => '1.0.0',
            'name'           => 'MyModule',
            'providers'      => ['Modules\\X\\Providers\\XServiceProvider'],
        ], 'module.json');

        expect($result->isValid())->toBeTrue();
    });

    test('manifest declaring unknown schema_version is rejected', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'schema_version' => '99.99.99',
            'name'           => 'MyModule',
            'providers'      => ['Modules\\X\\Providers\\XServiceProvider'],
        ], 'module.json');

        expect($result->isValid())->toBeFalse();
        expect(implode(' ', $result->errors()))->toContain('schema_version');
    });

    // ── AI manifest ───────────────────────────────────────────────────────────

    test('valid ai.manifest passes', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'module' => 'TitanNexus',
            'memory' => ['campaign_lessons', 'lead_preferences'],
        ], 'ai.manifest');

        expect($result->isValid())->toBeTrue();
    });

    test('ai.manifest missing required module field fails', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'primary_agent' => 'my-agent',
        ], 'ai.manifest');

        expect($result->isValid())->toBeFalse();
        expect(implode(' ', $result->errors()))->toContain('"module"');
    });

    // ── Billing manifest ──────────────────────────────────────────────────────

    test('valid billing.manifest passes', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'module' => 'TitanNexus',
            'meters' => ['lead_enrichment', 'voice_minutes'],
        ], 'billing.manifest');

        expect($result->isValid())->toBeTrue();
    });

    // ── Workflow manifest ─────────────────────────────────────────────────────

    test('valid workflow.manifest passes', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData([
            'module'    => 'TitanNexus',
            'workflows' => ['LeadToDealWorkflow'],
        ], 'workflow.manifest');

        expect($result->isValid())->toBeTrue();
    });

    // ── Unknown manifest type ─────────────────────────────────────────────────

    test('unknown manifest type returns warning, not failure', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateData(['foo' => 'bar'], 'custom.manifest');

        expect($result->isValid())->toBeTrue();
        expect($result->hasWarnings())->toBeTrue();
    });

    // ── File-based validation ─────────────────────────────────────────────────

    test('validateFile returns failure for non-existent file', function () {
        $v      = new ManifestSchemaValidator();
        $result = $v->validateFile('/no/such/path/module.json');

        expect($result->isValid())->toBeFalse();
    });

    test('validateFile returns failure for invalid JSON file', function () {
        $tmp = tempnam(sys_get_temp_dir(), 'manifest_') . '.json';
        file_put_contents($tmp, '{not valid json}');

        $v      = new ManifestSchemaValidator();
        $result = $v->validateFile($tmp, 'module.json');

        expect($result->isValid())->toBeFalse();

        unlink($tmp);
    });

    test('validateFile succeeds for a valid module.json', function () {
        $tmp = tempnam(sys_get_temp_dir(), 'manifest_') . '.json';
        file_put_contents($tmp, json_encode([
            'name'      => 'TestModule',
            'providers' => ['Modules\\TestModule\\Providers\\TestServiceProvider'],
        ]));

        $v      = new ManifestSchemaValidator();
        $result = $v->validateFile($tmp, 'module.json');

        expect($result->isValid())->toBeTrue();

        unlink($tmp);
    });

    // ── validateModule ────────────────────────────────────────────────────────

    test('validateModule returns results for module.json in directory', function () {
        $dir = sys_get_temp_dir() . '/titan_schema_test_' . uniqid();
        mkdir($dir, 0755, true);

        file_put_contents($dir . '/module.json', json_encode([
            'name'      => 'TestModule',
            'providers' => ['Modules\\TestModule\\Providers\\TestServiceProvider'],
        ]));

        $v       = new ManifestSchemaValidator();
        $results = $v->validateModule($dir);

        expect($results)->not->toBeEmpty();
        expect($results[0]->isValid())->toBeTrue();

        unlink($dir . '/module.json');
        rmdir($dir);
    });

    test('validateModule reports failure for invalid module.json', function () {
        $dir = sys_get_temp_dir() . '/titan_schema_test_' . uniqid();
        mkdir($dir, 0755, true);

        // Missing required "providers"
        file_put_contents($dir . '/module.json', json_encode([
            'name' => 'BadModule',
        ]));

        $v       = new ManifestSchemaValidator();
        $results = $v->validateModule($dir);

        $hasFailure = array_filter($results, fn ($r) => ! $r->isValid());
        expect($hasFailure)->not->toBeEmpty();

        unlink($dir . '/module.json');
        rmdir($dir);
    });

});
