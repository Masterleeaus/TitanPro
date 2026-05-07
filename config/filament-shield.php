<?php

/**
 * Filament Shield — default configuration.
 *
 * Publish this file with:
 *   php artisan vendor:publish --tag=filament-shield-config
 *
 * Then run:
 *   php artisan shield:generate --all
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Shield Resource
    |--------------------------------------------------------------------------
    | Configure the Shield resource that manages roles and permissions in the
    | Filament admin panel.
    */
    'shield_resource' => [
        'should_be_simple' => true,
        'slug'             => 'shield/roles',
        'navigation_sort'  => -1,
        'navigation_badge' => true,
        'navigation_group' => true,
        'is_globally_searchable' => false,
        'show_model_path'  => true,
        'generate_within_panels' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Provider Model
    |--------------------------------------------------------------------------
    */
    'auth_provider_model' => [
        'fqcn' => \App\Models\User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Super Admin
    |--------------------------------------------------------------------------
    | When enabled, a super_admin role is exempt from all permission checks.
    */
    'super_admin' => [
        'enabled'                              => true,
        'name'                                 => 'super_admin',
        'define_via_gate'                      => false,
        'intercept_gate'                       => 'before',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Prefixes
    |--------------------------------------------------------------------------
    */
    'permission_prefixes' => [
        'resource' => [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ],
        'page'   => 'page',
        'widget' => 'widget',
    ],

    /*
    |--------------------------------------------------------------------------
    | Entities
    |--------------------------------------------------------------------------
    | Control which Filament entity types Shield generates permissions for.
    */
    'entities' => [
        'pages'              => true,
        'widgets'            => true,
        'resources'          => true,
        'custom_permissions' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Generator
    |--------------------------------------------------------------------------
    */
    'generator' => [
        'option' => 'policies_and_permissions',
    ],

    /*
    |--------------------------------------------------------------------------
    | Exclude
    |--------------------------------------------------------------------------
    | Pages and widgets excluded from permission generation.
    */
    'exclude' => [
        'enabled' => true,
        'pages'   => [
            'Dashboard',
        ],
        'widgets' => [
            'AccountWidget',
            'InfoWidget',
        ],
        'resources' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Discovery
    |--------------------------------------------------------------------------
    */
    'discovery' => [
        'discover_all_resources' => false,
        'discover_all_widgets'   => false,
        'discover_all_pages'     => false,
    ],

];
