import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
const ListMenuItems = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMenuItems.url(options),
    method: 'get',
})

ListMenuItems.definition = {
    methods: ["get","head"],
    url: '/titanpro/menu-items',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
ListMenuItems.url = (options?: RouteQueryOptions) => {
    return ListMenuItems.definition.url + queryParams(options)
}

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
ListMenuItems.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMenuItems.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
ListMenuItems.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListMenuItems.url(options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
const ListMenuItemsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMenuItems.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
ListMenuItemsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMenuItems.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuItemResource/Pages/ListMenuItems.php:7
* @route '/titanpro/menu-items'
*/
ListMenuItemsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMenuItems.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListMenuItems.form = ListMenuItemsForm

export default ListMenuItems