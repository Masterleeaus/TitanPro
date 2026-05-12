<?php

test('asset module manifest includes description and capabilities metadata', function (): void {
    $manifestPath = dirname(__DIR__, 3).'/Modules/Asset/module.json';
    $manifest = json_decode(file_get_contents($manifestPath), true, flags: JSON_THROW_ON_ERROR);

    expect($manifest['description'] ?? null)->not->toBeEmpty()
        ->and($manifest['capabilities'] ?? [])->toBeArray()->not->toBeEmpty()
        ->and($manifest['providers'] ?? [])->toContain(
            'Modules\\Asset\\Providers\\AssetServiceProvider',
            'Modules\\Asset\\Providers\\RouteServiceProvider',
            'Modules\\Asset\\Providers\\AuthServiceProvider',
            'Modules\\Asset\\Providers\\EventServiceProvider',
        );
});

test('asset module routes are web-scoped and api route file is intentionally empty', function (): void {
    $root = dirname(__DIR__, 3);
    $webRoutes = file_get_contents($root.'/Modules/Asset/Routes/web.php');
    $apiRoutes = file_get_contents($root.'/Modules/Asset/Routes/api.php');

    expect($webRoutes)->toContain("Route::group(['middleware' => 'auth', 'prefix' => 'account']")
        ->and($webRoutes)->toContain("Route::resource('assets', AssetController::class)")
        ->and($apiRoutes)->toContain('// API routes are intentionally empty for this module.')
        ->and($apiRoutes)->not->toContain('Route::');
});

test('asset policy includes tenant boundary and permission checks', function (): void {
    $policy = file_get_contents(dirname(__DIR__, 3).'/Modules/Asset/Policies/AssetPolicy.php');

    expect($policy)->toContain('private function isTenantMatch')
        ->and($policy)->toContain('return $userTenantId > 0 && $assetTenantId > 0 && $userTenantId === $assetTenantId;')
        ->and($policy)->toContain("\$this->canByPermission(\$user, 'view_asset')")
        ->and($policy)->toContain("\$this->canByPermission(\$user, 'edit_asset')");
});

test('asset migration set includes tenant company_id coverage for core tables', function (): void {
    $root = dirname(__DIR__, 3);
    $companyIdMigration = file_get_contents($root.'/Modules/Asset/Database/Migrations/2022_09_02_000000_add_company_id_assets_module_table.php');
    $maintenanceMigration = file_get_contents($root.'/Modules/Asset/Database/Migrations/2026_02_23_1000_2022_03_26_062215_create_asset_maintenances_table.php');
    $transactionMigration = file_get_contents($root.'/Modules/Asset/Database/Migrations/2026_02_23_1000_2020_08_20_173031_create_asset_transactions_table.php');

    expect($companyIdMigration)->toContain("'asset_types', 'assets', 'asset_lending_history'")
        ->and($companyIdMigration)->toContain("if (! Schema::hasColumn(\$table, 'company_id'))")
        ->and($maintenanceMigration)->toContain("\$table->integer('company_id')->index();")
        ->and($transactionMigration)->toContain("\$table->integer('company_id')->unsigned();");
});
