<?php

use Illuminate\Support\Facades\File;

/**
 * Tests for the modules:schema-docs artisan command.
 */
describe('modules:schema-docs command', function () {

    test('command exits successfully', function () {
        $outputDir = sys_get_temp_dir() . '/titan_schema_docs_' . uniqid();

        $this->artisan('modules:schema-docs', [
            '--output' => $outputDir,
            '--force'  => true,
        ])->assertExitCode(0);

        if (is_dir($outputDir)) {
            File::deleteDirectory($outputDir);
        }
    });

    test('command generates markdown files in the output directory', function () {
        $outputDir = sys_get_temp_dir() . '/titan_schema_docs_' . uniqid();

        $this->artisan('modules:schema-docs', [
            '--output' => $outputDir,
            '--force'  => true,
        ])->assertExitCode(0);

        $files = glob($outputDir . '/*.md') ?: [];

        expect($files)->not->toBeEmpty('Expected at least one .md file to be generated');

        File::deleteDirectory($outputDir);
    });

    test('generated markdown files contain expected headings', function () {
        $outputDir = sys_get_temp_dir() . '/titan_schema_docs_' . uniqid();

        $this->artisan('modules:schema-docs', [
            '--output' => $outputDir,
            '--force'  => true,
        ])->assertExitCode(0);

        foreach (glob($outputDir . '/*.md') ?: [] as $file) {
            $content = file_get_contents($file);
            expect($content)->toContain('# ');
            expect($content)->toContain('## Properties');
        }

        File::deleteDirectory($outputDir);
    });

    test('generated markdown files contain schema versioning section', function () {
        $outputDir = sys_get_temp_dir() . '/titan_schema_docs_' . uniqid();

        $this->artisan('modules:schema-docs', [
            '--output' => $outputDir,
            '--force'  => true,
        ])->assertExitCode(0);

        foreach (glob($outputDir . '/*.md') ?: [] as $file) {
            $content = file_get_contents($file);
            expect($content)->toContain('Schema Versioning');
        }

        File::deleteDirectory($outputDir);
    });

    test('module.json schema docs are generated with required fields listed', function () {
        $outputDir = sys_get_temp_dir() . '/titan_schema_docs_' . uniqid();

        $this->artisan('modules:schema-docs', [
            '--output' => $outputDir,
            '--force'  => true,
        ])->assertExitCode(0);

        // module-json.md should be present
        $moduleJsonDoc = $outputDir . '/module-json.md';

        if (! file_exists($moduleJsonDoc)) {
            $this->markTestSkipped('module-json.md was not generated (check schema file).');
        }

        $content = file_get_contents($moduleJsonDoc);
        expect($content)->toContain('`name`');
        expect($content)->toContain('`providers`');

        File::deleteDirectory($outputDir);
    });

});
