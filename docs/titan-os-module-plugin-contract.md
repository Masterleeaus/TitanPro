# Titan OS Module Plug‑in Contract

This document describes the contract that modules must implement to integrate
with the Titan Business OS shell.  The goal of this contract is to allow
future modules to register themselves cleanly without modifying the core OS
code.  Modules can contribute an app definition for the launcher, provide a
workspace view, expose additional context to the assistant and supply their
own widgets for the generated UI renderer.

## Registering an App

To register an app with the launcher, implement the
`RegistersTitanOsApp` interface and return an array matching the keys of
`config/titan_os_apps.php`.  Example:

```php
use App\Support\TitanOS\Contracts\RegistersTitanOsApp;

class DispatchModule implements RegistersTitanOsApp
{
    public static function titanOsApp(): array
    {
        return [
            'key' => 'dispatch',
            'name' => 'Dispatch',
            'label' => 'Dispatch',
            'panel' => 'dispatch',
            'route' => '/dispatch',
            'icon' => 'truck',
            'category' => 'operations',
            'enabled' => true,
            'locked' => false,
            'upgrade_required' => false,
            'coming_soon' => false,
        ];
    }
}
```

## Providing a Workspace

Modules can supply a custom workspace by implementing the
`ProvidesTitanOsWorkspace` interface.  The method should return a Blade view
name which will be rendered when the user opens the app from the launcher.

```php
use App\Support\TitanOS\Contracts\ProvidesTitanOsWorkspace;

class DispatchModule implements ProvidesTitanOsWorkspace
{
    public static function titanOsWorkspace(): string
    {
        return 'modules.dispatch.workspace';
    }
}
```

## Extending Titan Zero Context

If a module needs to expose additional generic context fields to the Titan
Zero assistant, it can implement `ProvidesTitanZeroContext` and return a
key/value array.  Domain specific data (e.g. customer records, budgets) must
not be included.

```php
use App\Support\TitanOS\Contracts\ProvidesTitanZeroContext;

class DispatchModule implements ProvidesTitanZeroContext
{
    public static function titanZeroContext(): array
    {
        return [
            'dispatch_enabled' => true,
        ];
    }
}
```

## Providing Widgets

Modules can contribute their own widget schemas to the generated UI
renderer by implementing `ProvidesTitanOsWidgets`.  The array returned
should contain widget definitions that the `WidgetRenderer` understands.

```php
use App\Support\TitanOS\Contracts\ProvidesTitanOsWidgets;

class DispatchModule implements ProvidesTitanOsWidgets
{
    public static function titanOsWidgets(): array
    {
        return [
            [
                'type' => 'card',
                'title' => 'Dispatch Summary',
                'content' => 'No active jobs.',
            ],
        ];
    }
}
```

By adhering to this contract, modules can seamlessly extend the Business OS
without tightly coupling to its internals.