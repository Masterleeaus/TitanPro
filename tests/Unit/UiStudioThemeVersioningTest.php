<?php

test('ui studio theme versioning hooks are present', function () {
    $pageClass = file_get_contents(__DIR__.'/../../app/Filament/Pages/UiStudio.php');
    $bladeView = file_get_contents(__DIR__.'/../../resources/views/filament/pages/ui-studio.blade.php');

    expect($pageClass)
        ->toContain('public function saveNamedSnapshot(): void')
        ->toContain('public function rollbackThemeVersion(int $versionNumber): void')
        ->toContain('public function refreshVersionDiff(): void')
        ->toContain('$this->createThemeVersion(\'Auto snapshot before AI generation\');')
        ->toContain('$this->createThemeVersion(\'Auto snapshot before preset switch\');')
        ->toContain('$this->createThemeVersion($label !== \'\' ? $label : \'Manual save\');')
        ->and($bladeView)
        ->toContain('Theme history')
        ->toContain('wire:click="saveNamedSnapshot"')
        ->toContain('wire:click="rollbackThemeVersion(')
        ->toContain('wire:click="refreshVersionDiff"');
});

test('theme version persistence and rollback command are defined', function () {
    $matches = glob(__DIR__.'/../../database/migrations/*_create_titan_theme_versions_table.php');
    expect($matches)->not->toBeEmpty();

    $migration = file_get_contents($matches[0]);
    $model = file_get_contents(__DIR__.'/../../app/Models/TitanThemeVersion.php');
    $command = file_get_contents(__DIR__.'/../../app/Console/Commands/TitanThemeRollbackCommand.php');

    expect($migration)
        ->toContain("Schema::create('titan_theme_versions'")
        ->toContain("'org_id'")
        ->toContain("'panel'")
        ->toContain("'version_number'")
        ->toContain("'token_snapshot'")
        ->toContain("'label'")
        ->toContain("'created_by'")
        ->and($model)
        ->toContain('public const MAX_VERSIONS_PER_PANEL = 50;')
        ->toContain('public static function createSnapshot(')
        ->toContain('public static function pruneForPanel')
        ->and($command)
        ->toContain("protected \$signature = 'titan:theme:rollback {org} {version} {--panel=global}'");
});
