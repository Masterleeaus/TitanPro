import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
const ListModules = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListModules.url(options),
    method: 'get',
})

ListModules.definition = {
    methods: ["get","head"],
    url: '/titanpro/modules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
ListModules.url = (options?: RouteQueryOptions) => {
    return ListModules.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
ListModules.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListModules.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
ListModules.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListModules.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
const ListModulesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListModules.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
ListModulesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListModules.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanPro\Resources\ModuleResource\Pages\ListModules::__invoke
* @see app/Filament/TitanPro/Resources/ModuleResource/Pages/ListModules.php:7
* @route '/titanpro/modules'
*/
ListModulesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListModules.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListModules.form = ListModulesForm

export default ListModules