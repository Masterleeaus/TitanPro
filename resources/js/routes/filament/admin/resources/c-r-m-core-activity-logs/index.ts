import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/c-r-m-core-activity-logs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

const cRMCoreActivityLogs = {
    index: Object.assign(index, index),
}

export default cRMCoreActivityLogs