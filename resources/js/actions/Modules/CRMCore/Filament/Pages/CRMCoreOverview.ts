import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
const CRMCoreOverview = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CRMCoreOverview.url(options),
    method: 'get',
})

CRMCoreOverview.definition = {
    methods: ["get","head"],
    url: '/admin/c-r-m-core-overview',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
CRMCoreOverview.url = (options?: RouteQueryOptions) => {
    return CRMCoreOverview.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
CRMCoreOverview.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CRMCoreOverview.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
CRMCoreOverview.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CRMCoreOverview.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
const CRMCoreOverviewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CRMCoreOverview.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
CRMCoreOverviewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CRMCoreOverview.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Pages\CRMCoreOverview::__invoke
* @see Modules/CRMCore/Filament/Pages/CRMCoreOverview.php:7
* @route '/admin/c-r-m-core-overview'
*/
CRMCoreOverviewForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: CRMCoreOverview.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

CRMCoreOverview.form = CRMCoreOverviewForm

export default CRMCoreOverview