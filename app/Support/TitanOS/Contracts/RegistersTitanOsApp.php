<?php

namespace App\Support\TitanOS\Contracts;

/**
 * Modules can implement this interface to register themselves with the Titan
 * OS launcher.  The titanOsApp method should return an array with the same
 * keys as the definitions in config/titan_os_apps.php.  This allows new
 * modules to plug into the launcher without modifying core OS code.
 */
interface RegistersTitanOsApp
{
    /**
     * Return the app definition array.  Keys include: key, name, label,
     * panel, route, icon, category, enabled, locked, upgrade_required,
     * coming_soon, beta, hidden and disabled.
     *
     * @return array<string, mixed>
     */
    public static function titanOsApp(): array;
}