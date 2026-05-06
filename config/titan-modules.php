<?php

/**
 * Titan Modules — Module Discovery, Lifecycle, Paths, and Safe-Boot Settings
 *
 * This file controls how TitanCore discovers, loads, and manages modules.
 * It is the single source of truth for module infrastructure configuration.
 *
 * Required keys validated by TitanCoreServiceProvider::boot():
 *   - titan-modules.path
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Base Path
    |--------------------------------------------------------------------------
    |
    | The directory (relative to base_path()) where all Titan modules live.
    | Changing this requires updating nwidart/laravel-modules config too.
    |
    */

    'path' => env('TITAN_MODULES_PATH', 'Modules'),

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Root PHP namespace shared by all modules. Must match composer.json psr-4.
    |
    */

    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Safe-Boot Mode
    |--------------------------------------------------------------------------
    |
    | When true, a module that fails to register will be skipped rather than
    | crashing the entire application. Set to false in development to surface
    | errors immediately.
    |
    */

    'safe_boot' => env('TITAN_MODULES_SAFE_BOOT', true),

    /*
    |--------------------------------------------------------------------------
    | Discovery
    |--------------------------------------------------------------------------
    |
    | Controls automatic module discovery from the modules path. Disable if you
    | need deterministic ordering and register modules explicitly.
    |
    */

    'discovery' => [
        'enabled' => env('TITAN_MODULES_DISCOVERY', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Lifecycle
    |--------------------------------------------------------------------------
    |
    | auto_enable   — automatically mark newly discovered modules as enabled.
    | cache_manifests — cache parsed module.json manifests for performance.
    |
    */

    'lifecycle' => [
        'auto_enable'      => env('TITAN_MODULES_AUTO_ENABLE', false),
        'cache_manifests'  => env('TITAN_MODULES_CACHE_MANIFESTS', true),
    ],

];
