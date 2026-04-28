<?php

/**
 * Titan BOS — Panel Navigation Registry
 *
 * Defines the canonical list of Titan BOS product panels, their routes,
 * brand colours, and display metadata. Use this config to drive navigation
 * menus, panel-switcher links, and onboarding wizards across the platform.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Registered Panels
    |--------------------------------------------------------------------------
    |
    | Each entry maps a Filament panel ID to its metadata. The 'path' key must
    | match the ->path() value in the corresponding PanelProvider.
    |
    */

    'panels' => [

        'titanpro' => [
            'label'       => 'TitanPro',
            'description' => 'Super-admin SaaS control panel',
            'path'        => 'admin',
            'color'       => 'blue',
            'icon'        => 'heroicon-o-shield-check',
            'roles'       => ['super_admin'],
        ],

        'groundzero' => [
            'label'       => 'Ground Zero',
            'description' => 'Dispatch SPA — live job board and route management',
            'path'        => 'groundzero',
            'color'       => 'cyan',
            'icon'        => 'heroicon-o-map',
            'roles'       => ['owner', 'admin', 'dispatcher'],
        ],

        'titanquotes' => [
            'label'       => 'TitanQuotes',
            'description' => 'Estimating and quoting engine',
            'path'        => 'titanquotes',
            'color'       => 'emerald',
            'icon'        => 'heroicon-o-document-text',
            'roles'       => ['owner', 'admin', 'dispatcher'],
        ],

        'zeropay' => [
            'label'       => 'ZeroPay',
            'description' => 'Payments, invoicing and reconciliation',
            'path'        => 'zeropay',
            'color'       => 'violet',
            'icon'        => 'heroicon-o-credit-card',
            'roles'       => ['owner', 'admin', 'bookkeeper'],
        ],

        'titango' => [
            'label'       => 'TitanGo',
            'description' => 'Technician PWA bridge — field operations',
            'path'        => 'titango',
            'color'       => 'orange',
            'icon'        => 'heroicon-o-device-phone-mobile',
            'roles'       => ['technician', 'owner', 'admin'],
        ],

        'zerofuss' => [
            'label'       => 'ZeroFuss',
            'description' => 'Customer self-service portal',
            'path'        => 'zerofuss',
            'color'       => 'teal',
            'icon'        => 'heroicon-o-user-circle',
            'roles'       => ['customer'],
        ],

        'titansolo' => [
            'label'       => 'TitanSolo',
            'description' => 'Solo operator all-in-one dashboard',
            'path'        => 'titansolo',
            'color'       => 'sky',
            'icon'        => 'heroicon-o-user',
            'roles'       => ['owner'],
        ],

        'titanstudio' => [
            'label'       => 'TitanStudio',
            'description' => 'Creative hub — branding, content and collateral',
            'path'        => 'titanstudio',
            'color'       => 'pink',
            'icon'        => 'heroicon-o-paint-brush',
            'roles'       => ['owner', 'admin'],
        ],

        'titannexus' => [
            'label'       => 'TitanNexus',
            'description' => 'Growth intelligence (formerly TitanGrow)',
            'path'        => 'titannexus',
            'color'       => 'indigo',
            'icon'        => 'heroicon-o-arrow-trending-up',
            'roles'       => ['owner', 'admin'],
        ],

    ],

];
