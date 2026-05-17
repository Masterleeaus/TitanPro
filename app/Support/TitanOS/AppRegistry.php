<?php

namespace App\Support\TitanOS;

/**
 * The AppRegistry exposes a simple API to retrieve all registered Titan OS
 * applications.  Each app definition is hydrated into an AppDefinition
 * instance for convenience.  Future modules can register themselves by
 * publishing additional entries into the `titan_os_apps` config file or by
 * implementing the RegistersTitanOsApp contract.
 */
class AppRegistry
{
    /**
     * Return all registered apps as AppDefinition objects.
     *
     * @return array<int, AppDefinition>
     */
    public static function all(): array
    {
        $definitions = config('titan_os_apps', []);
        $apps = [];
        foreach ($definitions as $definition) {
            $apps[] = new AppDefinition($definition);
        }
        return $apps;
    }

    /**
     * Find a single application by its key.
     *
     * @param  string  $key
     * @return AppDefinition|null
     */
    public static function get(string $key): ?AppDefinition
    {
        return self::findByKey($key);
    }

    /**
     * Find an application by its key.
     *
     * @param  string  $key
     * @return AppDefinition|null
     */
    public static function findByKey(string $key): ?AppDefinition
    {
        foreach (self::all() as $app) {
            if ($app->key === $key) {
                return $app;
            }
        }
        return null;
    }

    /**
     * Find an application by its panel identifier.
     *
     * @param  string  $panel
     * @return AppDefinition|null
     */
    public static function findByPanel(string $panel): ?AppDefinition
    {
        foreach (self::all() as $app) {
            if ($app->panel === $panel) {
                return $app;
            }
        }
        return null;
    }

    /**
     * Find an application by its route slug.  Leading slashes are ignored.
     *
     * @param  string  $route
     * @return AppDefinition|null
     */
    public static function findByRoute(string $route): ?AppDefinition
    {
        $normalized = ltrim($route, '/');
        foreach (self::all() as $app) {
            $appRoute = ltrim($app->route, '/');
            if ($appRoute === $normalized) {
                return $app;
            }
        }
        return null;
    }

    /**
     * Find an application by a single path segment.  This will attempt to
     * match the segment against the app key, panel id and route slug in
     * priority order.
     *
     * @param  string  $segment
     * @return AppDefinition|null
     */
    public static function findByPathSegment(string $segment): ?AppDefinition
    {
        if ($segment === '') {
            return null;
        }
        // Try key first
        $app = self::findByKey($segment);
        if ($app) {
            return $app;
        }
        // Then panel id
        $app = self::findByPanel($segment);
        if ($app) {
            return $app;
        }
        // Finally route slug
        return self::findByRoute($segment);
    }
}