import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanSolo\Pages\Dashboard::__invoke
* @see app/Filament/TitanSolo/Pages/Dashboard.php:7
* @route '/titansolo'
*/
const Dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(options),
    method: 'get',
})

Dashboard.definition = {
    methods: ["get","head"],
    url: '/titansolo',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanSolo\Pages\Dashboard::__invoke
* @see app/Filament/TitanSolo/Pages/Dashboard.php:7
* @route '/titansolo'
*/
Dashboard.url = (options?: RouteQueryOptions) => {
    return Dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanSolo\Pages\Dashboard::__invoke
* @see app/Filament/TitanSolo/Pages/Dashboard.php:7
* @route '/titansolo'
*/
Dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Pages\Dashboard::__invoke
* @see app/Filament/TitanSolo/Pages/Dashboard.php:7
* @route '/titansolo'
*/
Dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanSolo\Pages\Dashboard::__invoke
* @see app/Filament/TitanSolo/Pages/Dashboard.php:7
* @route '/titansolo'
*/
const DashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Pages\Dashboard::__invoke
* @see app/Filament/TitanSolo/Pages/Dashboard.php:7
* @route '/titansolo'
*/
DashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanSolo\Pages\Dashboard::__invoke
* @see app/Filament/TitanSolo/Pages/Dashboard.php:7
* @route '/titansolo'
*/
DashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

Dashboard.form = DashboardForm

export default Dashboard