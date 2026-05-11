import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
const Dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(options),
    method: 'get',
})

Dashboard.definition = {
    methods: ["get","head"],
    url: '/zerofuss',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
Dashboard.url = (options?: RouteQueryOptions) => {
    return Dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
Dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
Dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
const DashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
*/
DashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroFuss\Pages\Dashboard::__invoke
* @see app/Filament/ZeroFuss/Pages/Dashboard.php:7
* @route '/zerofuss'
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