import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
const Reports = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Reports.url(options),
    method: 'get',
})

Reports.definition = {
    methods: ["get","head"],
    url: '/admin/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
Reports.url = (options?: RouteQueryOptions) => {
    return Reports.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
Reports.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Reports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
Reports.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Reports.url(options),
    method: 'head',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
const ReportsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Reports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
ReportsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Reports.url(options),
    method: 'get',
})

/**
* @see \App\Filament\Pages\Reports::__invoke
* @see app/Filament/Pages/Reports.php:7
* @route '/admin/reports'
*/
ReportsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Reports.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Reports.form = ReportsForm

export default Reports