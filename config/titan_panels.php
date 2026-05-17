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

        'admin' => [
            'label'       => 'Admin',
            'description' => 'System administration',
            'path'        => 'admin',
            'color'       => 'blue',
            'icon'        => 'heroicon-o-shield-check',
            'roles'       => ['super_admin'],
        ],

        'pro' => [
            'label'       => 'Titan Pro',
            'description' => 'Full admin system control',
            'path'        => 'pro',
            'color'       => 'blue',
            'icon'        => 'heroicon-o-squares-2x2',
            'roles'       => ['super_admin', 'admin', 'owner'],
        ],

        'groundzero' => [
            'label'       => 'Ground Zero',
            'description' => 'Business overview control panel',
            'path'        => 'groundzero',
            'color'       => 'cyan',
            'icon'        => 'heroicon-o-map',
            'roles'       => ['owner', 'admin', 'dispatcher', 'bookkeeper'],
        ],

        'titanquotes' => [
            'label'       => 'Titanquotes',
            'description' => 'Quotes and estimating',
            'path'        => 'titanquotes',
            'color'       => 'emerald',
            'icon'        => 'heroicon-o-document-text',
            'roles'       => ['owner', 'admin', 'bookkeeper'],
        ],

        'zeropay' => [
            'label'       => 'Zeropay',
            'description' => 'Payments and transactions',
            'path'        => 'zeropay',
            'color'       => 'violet',
            'icon'        => 'heroicon-o-credit-card',
            'roles'       => ['owner', 'admin', 'bookkeeper'],
        ],

        'titango' => [
            'label'       => 'Titan Go',
            'description' => 'Field app jobs and operations',
            'path'        => 'titango',
            'color'       => 'orange',
            'icon'        => 'heroicon-o-device-phone-mobile',
            'roles'       => ['owner', 'admin', 'super_admin'],
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
            'description' => 'Sole trader / single-operator cleaning business dashboard',
            'path'        => 'titansolo',
            'color'       => 'sky',
            'icon'        => 'heroicon-o-user',
            'roles'       => ['owner'],
        ],


        'titanzero' => [
            'label'       => 'Titan Zero',
            'description' => 'AI automation and operational intelligence',
            'path'        => 'titanzero',
            'color'       => 'emerald',
            'icon'        => 'heroicon-o-cpu-chip',
            'roles'       => ['owner', 'admin'],
        ],

        'titanstudio' => [
            'label'       => 'Titan Studio',
            'description' => 'Marketing ads and campaigns',
            'path'        => 'titanstudio',
            'color'       => 'pink',
            'icon'        => 'heroicon-o-paint-brush',
            'roles'       => ['owner', 'admin'],
        ],

        'titannexus' => [
            'label'       => 'Titan Nexus',
            'description' => 'Vertical expansion and lead finder',
            'path'        => 'titannexus',
            'color'       => 'indigo',
            'icon'        => 'heroicon-o-arrow-trending-up',
            'roles'       => ['owner', 'admin'],
        ],


        'titanecho' => [
            'label'       => 'Titan Echo',
            'description' => 'Front desk calls, texts, and communications',
            'path'        => 'titanecho',
            'color'       => 'purple',
            'icon'        => 'heroicon-o-phone',
            'roles'       => ['owner', 'admin', 'dispatcher'],
        ],

        'titanlocker' => [
            'label'       => 'TitanLocker',
            'description' => 'Assets, inventory, and suppliers',
            'path'        => 'titanlocker',
            'color'       => 'cyan',
            'icon'        => 'heroicon-o-lock-closed',
            'roles'       => ['owner', 'admin', 'bookkeeper'],
        ],

        'titanmoney' => [
            'label'       => 'Titan Money',
            'description' => 'Finance and accounting',
            'path'        => 'titanmoney',
            'color'       => 'green',
            'icon'        => 'heroicon-o-banknotes',
            'roles'       => ['owner', 'admin', 'bookkeeper'],
        ],

        'titanpixel' => [
            'label'       => 'Titan Pixel',
            'description' => 'Design and branding studio',
            'path'        => 'titanpixel',
            'color'       => 'blue',
            'icon'        => 'heroicon-o-swatch',
            'roles'       => ['owner', 'admin', 'marketing'],
        ],

        'titansocial' => [
            'label'       => 'Titan Social',
            'description' => 'Social media management',
            'path'        => 'titansocial',
            'color'       => 'pink',
            'icon'        => 'heroicon-o-share',
            'roles'       => ['owner', 'admin', 'marketing'],
        ],

        'titanteam' => [
            'label'       => 'Titan Team',
            'description' => 'Team management and HR',
            'path'        => 'titanteam',
            'color'       => 'indigo',
            'icon'        => 'heroicon-o-users',
            'roles'       => ['owner', 'admin'],
        ],

        'zeroissues' => [
            'label'       => 'ZeroIssues',
            'description' => 'Issue tracking and safety',
            'path'        => 'zeroissues',
            'color'       => 'violet',
            'icon'        => 'heroicon-o-exclamation-triangle',
            'roles'       => ['owner', 'admin', 'dispatcher'],
        ],

        'compliancesafety' => [
            'label'       => 'Compliance & Safety',
            'description' => 'Security, compliance, and risk controls',
            'path'        => 'compliancesafety',
            'color'       => 'amber',
            'icon'        => 'heroicon-o-shield-check',
            'roles'       => ['owner', 'admin', 'dispatcher', 'supervisor'],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | App Launcher
    |--------------------------------------------------------------------------
    |
    | Visual metadata used by the customised Filament app launcher skin.
    |
    */

    'launcher_apps' => [
        ['label' => 'TITAN PRO', 'description' => 'Full Admin System Control', 'path' => 'pro', 'url' => '/pro', 'icon_key' => 'shield', 'gradient' => 'linear-gradient(135deg,#2563eb,#0f172a)'],
        ['label' => 'GROUND ZERO', 'description' => 'Business Overview Control Panel', 'path' => 'groundzero', 'url' => '/groundzero', 'icon_key' => 'leaf', 'gradient' => 'linear-gradient(135deg,#65a30d,#14532d)'],
        ['label' => 'TITANQUOTES', 'description' => 'Quotes & Estimating', 'path' => 'titanquotes', 'url' => '/titanquotes', 'icon_key' => 'quote', 'gradient' => 'linear-gradient(135deg,#f97316,#7c2d12)'],
        ['label' => 'ZEROPAY', 'description' => 'Payments & Transactions', 'path' => 'zeropay', 'url' => '/zeropay', 'icon_key' => 'card', 'gradient' => 'linear-gradient(135deg,#06b6d4,#164e63)'],
        ['label' => 'TITAN GO', 'description' => 'Field App Jobs & Operations', 'path' => 'titango', 'url' => '/titango', 'icon_key' => 'truck', 'gradient' => 'linear-gradient(135deg,#ec4899,#7f1d1d)'],
        ['label' => 'TITAN ZERO', 'description' => 'AI Automation & Operational Intelligence', 'path' => 'titanzero', 'url' => '/titanzero', 'icon_key' => 'cpu', 'gradient' => 'linear-gradient(135deg,#10b981,#064e3b)'],
        ['label' => 'TITAN ECHO', 'description' => 'Front Desk Calls, Texts & Communications', 'path' => 'titanecho', 'url' => '/titanecho', 'icon_key' => 'headset', 'gradient' => 'linear-gradient(135deg,#a855f7,#3b0764)'],
        ['label' => 'TITAN STUDIO', 'description' => 'Marketing Ads & Campaigns', 'path' => 'titanstudio', 'url' => '/titanstudio', 'icon_key' => 'megaphone', 'gradient' => 'linear-gradient(135deg,#ef4444,#7f1d1d)'],
        ['label' => 'TITAN NEXUS', 'description' => 'Vertical Expansion & Lead Finder', 'path' => 'titannexus', 'url' => '/titannexus', 'icon_key' => 'chart', 'gradient' => 'linear-gradient(135deg,#2563eb,#172554)'],
        ['label' => 'TITANLOCKER', 'description' => 'Assets, Inventory & Suppliers', 'path' => 'titanlocker', 'url' => '/titanlocker', 'icon_key' => 'lock', 'gradient' => 'linear-gradient(135deg,#0891b2,#134e4a)'],
        ['label' => 'TITAN MONEY', 'description' => 'Finance & Accounting', 'path' => 'titanmoney', 'url' => '/titanmoney', 'icon_key' => 'money', 'gradient' => 'linear-gradient(135deg,#65a30d,#14532d)'],
        ['label' => 'TITAN PIXEL', 'description' => 'Design & Branding Studio', 'path' => 'titanpixel', 'url' => '/titanpixel', 'icon_key' => 'pixel', 'gradient' => 'linear-gradient(135deg,#2563eb,#1e1b4b)'],
        ['label' => 'TITAN SOCIAL', 'description' => 'Social Media Management', 'path' => 'titansocial', 'url' => '/titansocial', 'icon_key' => 'social', 'gradient' => 'linear-gradient(135deg,#ec4899,#831843)'],
        ['label' => 'TITAN TEAM', 'description' => 'Team Management & HR', 'path' => 'titanteam', 'url' => '/titanteam', 'icon_key' => 'team', 'gradient' => 'linear-gradient(135deg,#6366f1,#312e81)'],
        ['label' => 'ZEROISSUES', 'description' => 'Issue Tracking & Safety', 'path' => 'zeroissues', 'url' => '/zeroissues', 'icon_key' => 'warning', 'gradient' => 'linear-gradient(135deg,#a855f7,#581c87)'],
        ['label' => 'COMPLIANCE & SAFETY', 'description' => 'Security, Compliance & Risk Controls', 'path' => 'compliancesafety', 'url' => '/compliancesafety', 'icon_key' => 'shield', 'gradient' => 'linear-gradient(135deg,#f59e0b,#78350f)'],
    ],

];
