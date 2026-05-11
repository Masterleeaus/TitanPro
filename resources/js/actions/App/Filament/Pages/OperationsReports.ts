import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
const OperationsReports = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: OperationsReports.url(options),
    method: 'get',
})

OperationsReports.definition = {
    methods: ["get","head"],
    url: '/admin/operations-reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
OperationsReports.url = (options?: RouteQueryOptions) => {
    return OperationsReports.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
OperationsReports.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: OperationsReports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
OperationsReports.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: OperationsReports.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
const OperationsReportsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: OperationsReports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
OperationsReportsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: OperationsReports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\OperationsReports::__invoke
* @see app/Filament/Pages/OperationsReports.php:7
* @route '/admin/operations-reports'
*/
OperationsReportsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: OperationsReports.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

OperationsReports.form = OperationsReportsForm

export default OperationsReports