import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
const Dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(options),
    method: 'get',
})

Dashboard.definition = {
    methods: ["get","head"],
    url: '/zeropay',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
Dashboard.url = (options?: RouteQueryOptions) => {
    return Dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
Dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
Dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
const DashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
*/
DashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: Dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Filament\ZeroPay\Pages\Dashboard::__invoke
* @see app/Filament/ZeroPay/Pages/Dashboard.php:7
* @route '/zeropay'
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