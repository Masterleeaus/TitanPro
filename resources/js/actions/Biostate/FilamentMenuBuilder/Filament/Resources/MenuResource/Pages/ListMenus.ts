import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
const ListMenus = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMenus.url(options),
    method: 'get',
})

ListMenus.definition = {
    methods: ["get","head"],
    url: '/titanpro/menus',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
ListMenus.url = (options?: RouteQueryOptions) => {
    return ListMenus.definition.url + queryParams(options)
}

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
ListMenus.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListMenus.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
ListMenus.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListMenus.url(options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
const ListMenusForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMenus.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
ListMenusForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMenus.url(options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/ListMenus.php:7
* @route '/titanpro/menus'
*/
ListMenusForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListMenus.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListMenus.form = ListMenusForm

export default ListMenus