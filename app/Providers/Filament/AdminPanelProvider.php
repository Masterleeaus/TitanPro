<?php

namespace App\Providers\Filament;

use Illuminate\Support\ServiceProvider;

/**
 * @deprecated Migrated to TitanProPanelProvider (issue-126).
 *
 * The generic /admin panel has been renamed to the dedicated /titanpro
 * super-admin panel. This class is kept as a no-op stub to avoid breaking
 * references in older deployment scripts or IDE caches. It is no longer
 * registered in bootstrap/providers.php.
 *
 * @see TitanProPanelProvider
 */
class AdminPanelProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void {}
}
