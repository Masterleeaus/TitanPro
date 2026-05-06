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

    /*
    |--------------------------------------------------------------------------
    | Manifest Schema Validation
    |--------------------------------------------------------------------------
    |
    | strict_manifest_validation
    |   When true, any manifest that fails JSON Schema validation will prevent
    |   the full sync/boot from completing (throws ManifestValidationException).
    |   Set to false (warning mode) to log warnings but continue booting.
    |
    | schema_docs_output_path
    |   Directory where `modules:schema-docs` writes Markdown documentation
    |   generated from the JSON Schema files.
    |
    */

    'strict_manifest_validation' => env('TITAN_STRICT_MANIFEST_VALIDATION', false),

    'schema_docs_output_path' => env('TITAN_SCHEMA_DOCS_PATH', 'docs/schemas'),

];
