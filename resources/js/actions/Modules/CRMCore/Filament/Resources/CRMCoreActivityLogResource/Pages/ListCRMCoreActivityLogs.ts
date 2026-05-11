import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
const ListCRMCoreActivityLogs = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCRMCoreActivityLogs.url(options),
    method: 'get',
})

ListCRMCoreActivityLogs.definition = {
    methods: ["get","head"],
    url: '/admin/c-r-m-core-activity-logs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
ListCRMCoreActivityLogs.url = (options?: RouteQueryOptions) => {
    return ListCRMCoreActivityLogs.definition.url + queryParams(options)
}

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
ListCRMCoreActivityLogs.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCRMCoreActivityLogs.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
ListCRMCoreActivityLogs.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListCRMCoreActivityLogs.url(options),
    method: 'head',
})

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
const ListCRMCoreActivityLogsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCRMCoreActivityLogs.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
ListCRMCoreActivityLogsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCRMCoreActivityLogs.url(options),
    method: 'get',
})

/**
* @see \Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource\Pages\ListCRMCoreActivityLogs::__invoke
* @see Modules/CRMCore/Filament/Resources/CRMCoreActivityLogResource/Pages/ListCRMCoreActivityLogs.php:7
* @route '/admin/c-r-m-core-activity-logs'
*/
ListCRMCoreActivityLogsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: ListCRMCoreActivityLogs.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

ListCRMCoreActivityLogs.form = ListCRMCoreActivityLogsForm

export default ListCRMCoreActivityLogs