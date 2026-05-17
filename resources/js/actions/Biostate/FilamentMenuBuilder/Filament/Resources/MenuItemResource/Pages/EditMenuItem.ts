import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
const EditMenuItem = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditMenuItem.url(args, options),
    method: 'get',
})

EditMenuItem.definition = {
    methods: ["get","head"],
    url: '/titanpro/menu-items/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
EditMenuItem.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    if (Array.isArray(args)) {
        args = {
            record: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        record: args.record,
    }

    return EditMenuItem.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
EditMenuItem.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditMenuItem.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
EditMenuItem.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditMenuItem.url(args, options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
const EditMenuItemForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMenuItem.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
EditMenuItemForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMenuItem.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/EditMenuItem.php:7
* @route '/titanpro/menu-items/{record}/edit'
*/
EditMenuItemForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: EditMenuItem.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

EditMenuItem.form = EditMenuItemForm

export default EditMenuItem