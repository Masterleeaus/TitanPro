<?php

use Modules\Asset\Entities\Asset;
use Modules\Asset\Policies\AssetPolicy;

test('asset module manifest includes description and capabilities metadata', function (): void {
    $manifestPath = base_path('Modules/Asset/module.json');
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
    $webRoutes = file_get_contents(base_path('Modules/Asset/Routes/web.php'));
    $apiRoutes = file_get_contents(base_path('Modules/Asset/Routes/api.php'));

    expect($webRoutes)->toContain("Route::group(['middleware' => 'auth', 'prefix' => 'account']")
        ->and($webRoutes)->toContain("Route::resource('assets', AssetController::class)")
        ->and(trim($apiRoutes))->toBe('<?php'."\n".'// API routes are intentionally empty for this module.');
});

test('asset policy denies cross-tenant access and allows same-tenant access with permission', function (): void {
    $user = Mockery::mock(App\Models\User::class)->makePartial();
    $user->company_id = 7;
    $user->shouldReceive('permission')->with('view_asset')->andReturn('all');
    $user->shouldReceive('permission')->with('edit_asset')->andReturn('all');

    $sameTenantAsset = new Asset(['company_id' => 7]);
    $otherTenantAsset = new Asset(['company_id' => 8]);

    $policy = new AssetPolicy;

    expect($policy->view($user, $sameTenantAsset))->toBeTrue()
        ->and($policy->update($user, $sameTenantAsset))->toBeTrue()
        ->and($policy->view($user, $otherTenantAsset))->toBeFalse()
        ->and($policy->update($user, $otherTenantAsset))->toBeFalse();
});

test('asset migration set includes tenant company_id coverage for core tables', function (): void {
    $companyIdMigration = file_get_contents(base_path('Modules/Asset/Database/Migrations/2022_09_02_000000_add_company_id_assets_module_table.php'));
    $maintenanceMigration = file_get_contents(base_path('Modules/Asset/Database/Migrations/2026_02_23_1000_2022_03_26_062215_create_asset_maintenances_table.php'));
    $transactionMigration = file_get_contents(base_path('Modules/Asset/Database/Migrations/2026_02_23_1000_2020_08_20_173031_create_asset_transactions_table.php'));

    expect($companyIdMigration)->toContain("'asset_types', 'assets', 'asset_lending_history'")
        ->and($companyIdMigration)->toContain("if (! Schema::hasColumn(\$table, 'company_id'))")
        ->and($maintenanceMigration)->toContain("\$table->integer('company_id')->index();")
        ->and($transactionMigration)->toContain("\$table->integer('company_id')->unsigned();");
});
