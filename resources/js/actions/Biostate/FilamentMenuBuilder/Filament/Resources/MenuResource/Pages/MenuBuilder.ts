import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
const MenuBuilder = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MenuBuilder.url(args, options),
    method: 'get',
})

MenuBuilder.definition = {
    methods: ["get","head"],
    url: '/titanpro/menus/{record}/build',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
MenuBuilder.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return MenuBuilder.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
MenuBuilder.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MenuBuilder.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
MenuBuilder.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MenuBuilder.url(args, options),
    method: 'head',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
const MenuBuilderForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MenuBuilder.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
MenuBuilderForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MenuBuilder.url(args, options),
    method: 'get',
})

/**
* @see \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::__invoke
* @see vendor/biostate/filament-menu-builder/src/Filament/Resources/MenuResource/Pages/MenuBuilder.php:7
* @route '/titanpro/menus/{record}/build'
*/
MenuBuilderForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: MenuBuilder.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

MenuBuilder.form = MenuBuilderForm

export default MenuBuilder