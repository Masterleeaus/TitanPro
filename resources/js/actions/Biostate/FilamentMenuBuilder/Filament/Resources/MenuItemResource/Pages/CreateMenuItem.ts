import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
const CreateMenuItem = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateMenuItem.url(options),
    method: 'get',
})

CreateMenuItem.definition = {
    methods: ["get","head"],
    url: '/titanpro/menu-items/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
CreateMenuItem.url = (options?: RouteQueryOptions) => {
    return CreateMenuItem.definition.url + queryParams(options)
}

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
CreateMenuItem.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateMenuItem.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
CreateMenuItem.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateMenuItem.url(options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
const CreateMenuItemForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateMenuItem.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
CreateMenuItemForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateMenuItem.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/CreateMenuItem.php:7
* @route '/titanpro/menu-items/create'
*/
CreateMenuItemForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CreateMenuItem.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CreateMenuItem.form = CreateMenuItemForm

export default CreateMenuItem