import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\TitanGo\Pages\Dashboard::__invoke
* @see app/Filament/TitanGo/Pages/Dashboard.php:7
* @route '/titango'
*/
const Dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(options),
    method: 'get',
})

Dashboard.definition = {
    methods: ["get","head"],
    url: '/titango',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\TitanGo\Pages\Dashboard::__invoke
* @see app/Filament/TitanGo/Pages/Dashboard.php:7
* @route '/titango'
*/
Dashboard.url = (options?: RouteQueryOptions) => {
    return Dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\TitanGo\Pages\Dashboard::__invoke
* @see app/Filament/TitanGo/Pages/Dashboard.php:7
* @route '/titango'
*/
Dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanGo\Pages\Dashboard::__invoke
* @see app/Filament/TitanGo/Pages/Dashboard.php:7
* @route '/titango'
*/
Dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\TitanGo\Pages\Dashboard::__invoke
* @see app/Filament/TitanGo/Pages/Dashboard.php:7
* @route '/titango'
*/
const DashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanGo\Pages\Dashboard::__invoke
* @see app/Filament/TitanGo/Pages/Dashboard.php:7
* @route '/titango'
*/
DashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\TitanGo\Pages\Dashboard::__invoke
* @see app/Filament/TitanGo/Pages/Dashboard.php:7
* @route '/titango'
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